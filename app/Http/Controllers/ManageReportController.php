<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\Notification;
use App\Models\ReportHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageReportController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureManager();

        $search = $request->search;
        $status = $request->status;

        $reports = IssueReport::with([
                'reporter',
                'parkingLocation',
                'assignedTechnician',
                'verifier',
            ])
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
                        })
                        ->orWhereHas('assignedTechnician', function ($technicianQuery) use ($search) {
                            $technicianQuery->where('name', 'like', "%{$search}%")
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

        return view('manage-reports.index', compact('reports', 'search', 'status'));
    }

    public function show(IssueReport $issueReport)
    {
        $this->ensureManager();

        $issueReport->load([
            'reporter',
            'parkingLocation',
            'assignedTechnician',
            'verifier',
            'followUps.technician',
            'followUps.backupItem',
            'histories.user',
        ]);

        $technicians = User::where('role', 'teknisi')
            ->where('status', 'Aktif')
            ->orderBy('full_name')
            ->orderBy('name')
            ->get();

        return view('manage-reports.show', compact('issueReport', 'technicians'));
    }

    public function verifyAndAssign(Request $request, IssueReport $issueReport)
    {
        $this->ensureManager();

        if ($issueReport->status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Laporan hanya dapat diverifikasi jika status masih Menunggu Verifikasi.');
        }

        $validated = $request->validate([
            'assigned_technician_id' => 'required|exists:users,id',
            'verification_note' => 'nullable|string',
        ], [
            'assigned_technician_id.required' => 'Teknisi Vendor wajib dipilih.',
            'assigned_technician_id.exists' => 'Teknisi Vendor tidak valid.',
        ]);

        $technician = User::where('id', $validated['assigned_technician_id'])
            ->where('role', 'teknisi')
            ->where('status', 'Aktif')
            ->first();

        if (!$technician) {
            return back()
                ->withInput()
                ->withErrors([
                    'assigned_technician_id' => 'Teknisi Vendor tidak aktif atau tidak valid.',
                ]);
        }

        $previousStatus = $issueReport->status;

        $issueReport->update([
            'status' => 'Dalam Proses',
            'assigned_technician_id' => $technician->id,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'verification_note' => $validated['verification_note'] ?? null,
        ]);

        ReportHistory::create([
            'issue_report_id' => $issueReport->id,
            'user_id' => Auth::id(),
            'action' => 'verified_assigned',
            'previous_status' => $previousStatus,
            'new_status' => 'Dalam Proses',
            'notes' => 'Laporan diverifikasi dan ditugaskan kepada Teknisi Vendor.',
            'metadata' => [
                'assigned_technician_id' => $technician->id,
                'assigned_technician_name' => $technician->full_name ?? $technician->name,
                'verified_by' => Auth::user()->full_name ?? Auth::user()->name,
            ],
        ]);

        Notification::create([
            'user_id' => $technician->id,
            'title' => 'Tugas Penanganan Baru',
            'message' => 'Anda mendapatkan tugas penanganan laporan kendala parkir nomor ' . ($issueReport->report_number ?? '-') . '.',
            'type' => 'report_assignment',
            'reference_id' => $issueReport->id,
            'reference_type' => 'issue_reports',
            'url' => route('technician-reports.show', $issueReport),
        ]);

        Notification::create([
            'user_id' => $issueReport->user_id,
            'title' => 'Laporan Diverifikasi',
            'message' => 'Laporan kendala Anda telah diverifikasi dan sedang dalam proses penanganan.',
            'type' => 'report',
            'reference_id' => $issueReport->id,
            'reference_type' => 'issue_reports',
            'url' => route('issue-reports.show', $issueReport),
        ]);

        return redirect()
            ->route('manage-reports.show', $issueReport)
            ->with('success', 'Laporan berhasil diverifikasi dan ditugaskan ke Teknisi Vendor.');
    }

    public function reject(Request $request, IssueReport $issueReport)
    {
        $this->ensureManager();

        if (!in_array($issueReport->status, ['Menunggu Verifikasi', 'Dalam Proses', 'Menunggu Informasi'], true)) {
            return back()->with('error', 'Laporan dengan status ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $previousStatus = $issueReport->status;

        $issueReport->update([
            'status' => 'Ditolak',
            'verified_by' => Auth::id(),
            'verified_at' => $issueReport->verified_at ?? now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        ReportHistory::create([
            'issue_report_id' => $issueReport->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'previous_status' => $previousStatus,
            'new_status' => 'Ditolak',
            'notes' => 'Laporan ditolak oleh Manajer Operasional.',
            'metadata' => [
                'reason' => $validated['rejection_reason'],
                'rejected_by' => Auth::user()->full_name ?? Auth::user()->name,
            ],
        ]);

        Notification::create([
            'user_id' => $issueReport->user_id,
            'title' => 'Laporan Ditolak',
            'message' => 'Laporan kendala Anda ditolak oleh Manajer Operasional.',
            'type' => 'report',
            'reference_id' => $issueReport->id,
            'reference_type' => 'issue_reports',
            'url' => route('issue-reports.show', $issueReport),
        ]);

        return redirect()
            ->route('manage-reports.show', $issueReport)
            ->with('success', 'Laporan berhasil ditolak.');
    }

    public function close(IssueReport $issueReport)
    {
        $this->ensureManager();

        if ($issueReport->status !== 'Selesai Ditangani') {
            return back()->with('error', 'Laporan hanya dapat ditutup jika status sudah Selesai Ditangani.');
        }

        $previousStatus = $issueReport->status;

        $issueReport->update([
            'status' => 'Ditutup / Diarsipkan',
            'closed_at' => now(),
        ]);

        ReportHistory::create([
            'issue_report_id' => $issueReport->id,
            'user_id' => Auth::id(),
            'action' => 'closed',
            'previous_status' => $previousStatus,
            'new_status' => 'Ditutup / Diarsipkan',
            'notes' => 'Laporan ditutup dan diarsipkan oleh Manajer Operasional.',
            'metadata' => [
                'closed_by' => Auth::user()->full_name ?? Auth::user()->name,
            ],
        ]);

        Notification::create([
            'user_id' => $issueReport->user_id,
            'title' => 'Laporan Ditutup',
            'message' => 'Laporan kendala Anda telah ditutup dan diarsipkan.',
            'type' => 'report',
            'reference_id' => $issueReport->id,
            'reference_type' => 'issue_reports',
            'url' => route('issue-reports.show', $issueReport),
        ]);

        return redirect()
            ->route('manage-reports.show', $issueReport)
            ->with('success', 'Laporan berhasil ditutup dan diarsipkan.');
    }

    private function ensureManager(): void
    {
        if (Auth::user()->role !== 'manajer') {
            abort(403, 'Hanya Manajer Operasional yang dapat mengakses halaman ini.');
        }
    }
}