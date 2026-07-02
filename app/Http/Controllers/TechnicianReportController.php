<?php

namespace App\Http\Controllers;

use App\Models\BackupItem;
use App\Models\IssueReport;
use App\Models\Notification;
use App\Models\ReportFollowUp;
use App\Models\ReportHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicianReportController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureTechnician();

        $search = $request->search;
        $status = $request->status;

        $reports = IssueReport::with([
                'reporter',
                'parkingLocation',
                'assignedTechnician',
                'verifier',
            ])
            ->where('assigned_technician_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('report_number', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('priority', 'like', "%{$search}%")
                        ->orWhereHas('parkingLocation', function ($locationQuery) use ($search) {
                            $locationQuery->where('location_name', 'like', "%{$search}%")
                                ->orWhere('location_code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('reporter', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('full_name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('technician-reports.index', compact('reports', 'search', 'status'));
    }

    public function show(IssueReport $issueReport)
    {
        $this->ensureTechnician();
        $this->ensureAssignedToCurrentTechnician($issueReport);

        $issueReport->load([
            'reporter',
            'parkingLocation',
            'assignedTechnician',
            'verifier',
            'followUps.technician',
            'followUps.backupItem',
            'histories.user',
        ]);

        $backupItems = BackupItem::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->orderBy('item_name')
            ->get();

        return view('technician-reports.show', compact('issueReport', 'backupItems'));
    }

    public function updateStatus(Request $request, IssueReport $issueReport)
    {
        $this->ensureTechnician();
        $this->ensureAssignedToCurrentTechnician($issueReport);

        if (in_array($issueReport->status, ['Ditolak', 'Ditutup / Diarsipkan'], true)) {
            return back()->with('error', 'Laporan ini sudah tidak dapat diperbarui.');
        }

        $validated = $request->validate([
            'new_status' => 'required|in:Dalam Proses,Menunggu Informasi,Selesai Ditangani',
            'follow_up_note' => 'required|string',
            'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'need_backup_item' => 'nullable|boolean',
            'backup_item_id' => 'nullable|exists:backup_items,id',
            'backup_item_quantity' => 'nullable|integer|min:1',
            'backup_item_note' => 'nullable|string',
        ], [
            'new_status.required' => 'Status baru wajib dipilih.',
            'new_status.in' => 'Status baru tidak valid.',
            'follow_up_note.required' => 'Catatan hasil penanganan wajib diisi.',
            'documentation_photo.image' => 'File dokumentasi harus berupa gambar.',
            'documentation_photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'documentation_photo.max' => 'Ukuran foto maksimal 2 MB.',
            'backup_item_id.exists' => 'Barang backup tidak valid.',
            'backup_item_quantity.integer' => 'Jumlah barang backup harus berupa angka.',
            'backup_item_quantity.min' => 'Jumlah barang backup minimal 1.',
        ]);

        $needBackupItem = $request->boolean('need_backup_item');

        if ($needBackupItem && empty($validated['backup_item_id'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'backup_item_id' => 'Barang backup wajib dipilih jika membutuhkan barang backup.',
                ]);
        }

        if ($needBackupItem && empty($validated['backup_item_quantity'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'backup_item_quantity' => 'Jumlah barang backup wajib diisi jika membutuhkan barang backup.',
                ]);
        }

        if ($needBackupItem) {
            $backupItem = BackupItem::where('id', $validated['backup_item_id'])
                ->where('status', 'Tersedia')
                ->first();

            if (!$backupItem) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'backup_item_id' => 'Barang backup tidak tersedia.',
                    ]);
            }

            if ($backupItem->stock < $validated['backup_item_quantity']) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'backup_item_quantity' => 'Stok barang backup tidak mencukupi. Stok tersedia: ' . $backupItem->stock . '.',
                    ]);
            }
        }

        $photoPath = null;

        if ($request->hasFile('documentation_photo')) {
            $photoPath = $request->file('documentation_photo')->store('report-follow-ups', 'public');
        }

        $previousStatus = $issueReport->status;
        $newStatus = $validated['new_status'];

        ReportFollowUp::create([
            'issue_report_id' => $issueReport->id,
            'technician_id' => Auth::id(),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'follow_up_note' => $validated['follow_up_note'],
            'documentation_photo' => $photoPath,
            'need_backup_item' => $needBackupItem,
            'backup_item_id' => $needBackupItem ? $validated['backup_item_id'] : null,
            'backup_item_quantity' => $needBackupItem ? $validated['backup_item_quantity'] : null,
            'backup_item_note' => $needBackupItem ? ($validated['backup_item_note'] ?? null) : null,
        ]);

        $issueReport->update([
            'status' => $newStatus,
        ]);

        ReportHistory::create([
            'issue_report_id' => $issueReport->id,
            'user_id' => Auth::id(),
            'action' => 'technician_update',
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'notes' => 'Teknisi Vendor melakukan update penanganan laporan.',
            'metadata' => [
                'technician' => Auth::user()->full_name ?? Auth::user()->name,
                'need_backup_item' => $needBackupItem,
                'backup_item_id' => $needBackupItem ? $validated['backup_item_id'] : null,
                'backup_item_quantity' => $needBackupItem ? $validated['backup_item_quantity'] : null,
            ],
        ]);

        Notification::create([
            'user_id' => $issueReport->user_id,
            'title' => 'Update Penanganan Laporan',
            'message' => 'Teknisi Vendor telah memperbarui status laporan kendala Anda menjadi ' . $newStatus . '.',
            'type' => 'report_update',
            'reference_id' => $issueReport->id,
            'reference_type' => 'issue_reports',
            'url' => route('issue-reports.show', $issueReport),
        ]);

        $managers = User::where('role', 'manajer')
            ->where('status', 'Aktif')
            ->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Update Laporan dari Teknisi',
                'message' => 'Teknisi Vendor telah memperbarui status laporan kendala menjadi ' . $newStatus . '.',
                'type' => 'report_update',
                'reference_id' => $issueReport->id,
                'reference_type' => 'issue_reports',
                'url' => route('manage-reports.show', $issueReport),
            ]);
        }

        return redirect()
            ->route('technician-reports.show', $issueReport)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    private function ensureTechnician(): void
    {
        if (Auth::user()->role !== 'teknisi') {
            abort(403, 'Hanya Teknisi Vendor yang dapat mengakses halaman ini.');
        }
    }

    private function ensureAssignedToCurrentTechnician(IssueReport $issueReport): void
    {
        if ((int) $issueReport->assigned_technician_id !== (int) Auth::id()) {
            abort(403, 'Laporan ini tidak ditugaskan kepada Anda.');
        }
    }
}