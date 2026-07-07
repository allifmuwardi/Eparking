<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\Notification;
use App\Models\ParkingLocation;
use App\Models\ReportHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;

        if (!$user->parking_location_id) {
            $reports = IssueReport::query()
                ->whereRaw('1 = 0')
                ->paginate(10)
                ->withQueryString();

            return view('reports.issue-reports.index', compact('reports', 'search'))
                ->with('warning', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        /*
        |--------------------------------------------------------------------------
        | History Berdasarkan Lokasi Operasional
        |--------------------------------------------------------------------------
        | Petugas yang berada di lokasi/cabang yang sama akan melihat history
        | laporan kendala yang sama, walaupun laporan dibuat oleh akun berbeda.
        */
        $reports = IssueReport::with(['parkingLocation', 'assignedTechnician', 'reporter'])
            ->where('parking_location_id', $user->parking_location_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('report_number', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('priority', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('reporter', function ($reporterQuery) use ($search) {
                            $reporterQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.issue-reports.index', compact('reports', 'search'));
    }

    public function create()
    {
        $user = Auth::user();

        if (!$user->parking_location_id) {
            return redirect()
                ->route('issue-reports.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        /*
        |--------------------------------------------------------------------------
        | Lokasi Otomatis dari User Login
        |--------------------------------------------------------------------------
        | Variabel $locations tetap dikirim supaya view lama tidak error.
        | Tetapi isinya hanya lokasi operasional milik user.
        */
        $locations = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();

        $selectedLocation = $locations->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('issue-reports.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        return view('reports.issue-reports.create', compact('locations', 'selectedLocation'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->parking_location_id) {
            return redirect()
                ->route('issue-reports.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $selectedLocation = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('issue-reports.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        | parking_location_id tidak divalidasi dari input user lagi.
        | Lokasi laporan dikunci mengikuti lokasi operasional user login.
        */
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:Rendah,Sedang,Tinggi,Darurat',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'title.required' => 'Judul kendala wajib diisi.',
            'category.required' => 'Kategori kendala wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'description.required' => 'Deskripsi kendala wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('issue-reports', 'public');
        }

        $report = IssueReport::create([
            'report_number' => $this->generateReportNumber(),
            'user_id' => $user->id,
            'parking_location_id' => $user->parking_location_id,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        ReportHistory::create([
            'issue_report_id' => $report->id,
            'user_id' => $user->id,
            'action' => 'created',
            'previous_status' => null,
            'new_status' => 'Menunggu Verifikasi',
            'notes' => 'Laporan kendala dibuat oleh Petugas Parkir.',
            'metadata' => [
                'report_number' => $report->report_number,
                'created_by' => $user->full_name ?? $user->name,
                'parking_location_id' => $user->parking_location_id,
                'parking_location' => $selectedLocation->location_name ?? null,
                'area_zone' => $selectedLocation->area_zone ?? null,
            ],
        ]);

        $managers = User::where('role', 'manajer')
            ->where('status', 'Aktif')
            ->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Laporan Kendala Baru',
                'message' => 'Terdapat laporan kendala baru dari ' . ($selectedLocation->location_name ?? 'lokasi operasional') . ' yang menunggu verifikasi.',
                'type' => 'report',
                'reference_id' => $report->id,
                'reference_type' => 'issue_reports',
                'url' => route('manage-reports.show', $report),
            ]);
        }

        return redirect()
            ->route('issue-reports.index')
            ->with('success', 'Laporan kendala berhasil dikirim dan menunggu verifikasi Manajer Operasional.');
    }

    public function show(IssueReport $issueReport)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Akses Detail Petugas
        |--------------------------------------------------------------------------
        | Petugas boleh membuka laporan selama lokasi operasionalnya sama.
        | Manajer/Admin/Teknisi tetap boleh sesuai role operasional.
        */
        if ($user->role === 'petugas') {
            if (!$user->parking_location_id || $issueReport->parking_location_id !== $user->parking_location_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        } elseif (!in_array($user->role, ['manajer', 'admin', 'teknisi'], true)) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $issueReport->load([
            'reporter',
            'parkingLocation',
            'assignedTechnician',
            'verifier',
            'followUps.technician',
            'followUps.backupItem',
            'histories.user',
        ]);

        return view('reports.issue-reports.show', compact('issueReport'));
    }

    private function generateReportNumber(): string
    {
        $date = now()->format('Ymd');

        $lastReport = IssueReport::whereDate('created_at', now()->toDateString())
            ->latest('id')
            ->first();

        $nextNumber = $lastReport ? ((int) substr($lastReport->report_number, -4)) + 1 : 1;

        return 'RPT-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}