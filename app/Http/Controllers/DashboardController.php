<?php

namespace App\Http\Controllers;

use App\Models\BackupItem;
use App\Models\BackupRequest;
use App\Models\DailyTrafficReport;
use App\Models\IssueReport;
use App\Models\Notification;
use App\Models\ParkingLocation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if (!in_array($role, ['petugas', 'teknisi', 'manajer', 'admin'], true)) {
            abort(403, 'Akun ini tidak memiliki akses ke dashboard operasional parkir.');
        }

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $data = [
            'user' => $user,
            'role' => $role,
            'unreadNotifications' => $unreadNotifications,
        ];

        /*
        |--------------------------------------------------------------------------
        | Dashboard Petugas Parkir
        |--------------------------------------------------------------------------
        | History Petugas berdasarkan lokasi operasional yang sama.
        | Jadi jika Petugas A dan Petugas B berada di cabang/lokasi yang sama,
        | keduanya dapat melihat history laporan, traffic, dan backup pada lokasi itu.
        */
        if ($role === 'petugas') {
            $parkingLocationId = $user->parking_location_id;

            $issueReportLocationQuery = IssueReport::query();

            $trafficReportLocationQuery = DailyTrafficReport::query();

            $backupRequestLocationQuery = BackupRequest::query();

            if ($parkingLocationId) {
                $issueReportLocationQuery->where('parking_location_id', $parkingLocationId);
                $trafficReportLocationQuery->where('parking_location_id', $parkingLocationId);
                $backupRequestLocationQuery->where('parking_location_id', $parkingLocationId);
            } else {
                /*
                |--------------------------------------------------------------------------
                | Safety fallback
                |--------------------------------------------------------------------------
                | Jika akun Petugas belum diset lokasi operasionalnya,
                | jangan tampilkan semua data lokasi lain.
                */
                $issueReportLocationQuery->whereRaw('1 = 0');
                $trafficReportLocationQuery->whereRaw('1 = 0');
                $backupRequestLocationQuery->whereRaw('1 = 0');
            }

            $data = array_merge($data, [
                'myIssueReports' => (clone $issueReportLocationQuery)->count(),

                'myPendingReports' => (clone $issueReportLocationQuery)
                    ->where('status', 'Menunggu Verifikasi')
                    ->count(),

                'myProcessReports' => (clone $issueReportLocationQuery)
                    ->where('status', 'Dalam Proses')
                    ->count(),

                'myTrafficReports' => (clone $trafficReportLocationQuery)->count(),

                'myBackupRequests' => (clone $backupRequestLocationQuery)->count(),

                'latestIssueReports' => (clone $issueReportLocationQuery)
                    ->with(['parkingLocation', 'reporter'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'latestTrafficReports' => (clone $trafficReportLocationQuery)
                    ->with(['parkingLocation', 'user'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'latestBackupRequests' => (clone $backupRequestLocationQuery)
                    ->with(['requester', 'parkingLocation', 'backupItem'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'operationalLocation' => $user->operational_location_label ?? 'Belum ditentukan',
                'hasOperationalLocation' => !empty($parkingLocationId),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Teknisi Vendor
        |--------------------------------------------------------------------------
        | Teknisi tetap fokus pada laporan yang ditugaskan oleh Manajer Operasional.
        | Lokasi operasional digunakan sebagai identitas area/cabang teknisi.
        */
        if ($role === 'teknisi') {
            $data = array_merge($data, [
                'assignedReports' => IssueReport::where('assigned_technician_id', $user->id)->count(),

                'processReports' => IssueReport::where('assigned_technician_id', $user->id)
                    ->where('status', 'Dalam Proses')
                    ->count(),

                'waitingInfoReports' => IssueReport::where('assigned_technician_id', $user->id)
                    ->where('status', 'Menunggu Informasi')
                    ->count(),

                'finishedReports' => IssueReport::where('assigned_technician_id', $user->id)
                    ->where('status', 'Selesai Ditangani')
                    ->count(),

                'latestAssignedReports' => IssueReport::with(['parkingLocation', 'reporter'])
                    ->where('assigned_technician_id', $user->id)
                    ->latest()
                    ->limit(5)
                    ->get(),

                'operationalLocation' => $user->operational_location_label ?? 'Belum ditentukan',
                'hasOperationalLocation' => !empty($user->parking_location_id),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Manajer Operasional
        |--------------------------------------------------------------------------
        | Manajer fokus pada monitoring, verifikasi laporan kendala,
        | approval permintaan backup, traffic, dan laporan rekap.
        */
        if ($role === 'manajer') {
            $issueStatusLabels = [
                'Menunggu Verifikasi',
                'Dalam Proses',
                'Menunggu Informasi',
                'Selesai Ditangani',
                'Ditolak',
                'Ditutup / Diarsipkan',
            ];

            $issueStatusCounts = [];

            foreach ($issueStatusLabels as $status) {
                $issueStatusCounts[] = IssueReport::where('status', $status)->count();
            }

            $backupStatusLabels = [
                'Menunggu Verifikasi',
                'Disetujui',
                'Ditolak',
                'Dalam Proses',
                'Selesai',
            ];

            $backupStatusCounts = [];

            foreach ($backupStatusLabels as $status) {
                $backupStatusCounts[] = BackupRequest::where('status', $status)->count();
            }

            $trafficThisMonthQuery = DailyTrafficReport::whereMonth('report_date', now()->month)
                ->whereYear('report_date', now()->year);

            $trafficReportsThisMonth = (clone $trafficThisMonthQuery)->count();

            $totalTrafficIncomeThisMonth = (clone $trafficThisMonthQuery)->get()->sum(function ($traffic) {
                return $traffic->income
                    ?? $traffic->revenue
                    ?? $traffic->total_income
                    ?? $traffic->daily_income
                    ?? $traffic->total_revenue
                    ?? 0;
            });

            $totalVehiclesThisMonth = (clone $trafficThisMonthQuery)->get()->sum(function ($traffic) {
                return
                    ($traffic->car_count ?? $traffic->total_car ?? 0)
                    + ($traffic->motorcycle_count ?? $traffic->motor_count ?? $traffic->total_motorcycle ?? 0)
                    + ($traffic->other_vehicle_count ?? $traffic->other_count ?? 0);
            });

            $data = array_merge($data, [
                'totalIssueReports' => IssueReport::count(),

                'waitingVerificationReports' => IssueReport::where('status', 'Menunggu Verifikasi')->count(),

                'processReports' => IssueReport::where('status', 'Dalam Proses')->count(),

                'finishedReports' => IssueReport::where('status', 'Selesai Ditangani')->count(),

                'trafficReportsToday' => DailyTrafficReport::whereDate('report_date', today())->count(),

                'trafficReportsThisMonth' => $trafficReportsThisMonth,

                'totalTrafficIncomeThisMonth' => $totalTrafficIncomeThisMonth,

                'totalVehiclesThisMonth' => $totalVehiclesThisMonth,

                /*
                |--------------------------------------------------------------------------
                | Backup Approval Monitoring
                |--------------------------------------------------------------------------
                | Manajer hanya approve/reject.
                | Setelah disetujui, Admin Operasional yang memproses penyerahan barang.
                */
                'backupRequestsWaiting' => BackupRequest::where('status', 'Menunggu Verifikasi')->count(),

                'backupRequestsApproved' => BackupRequest::where('status', 'Disetujui')->count(),

                'backupRequestsThisMonth' => BackupRequest::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),

                'issueStatusLabels' => $issueStatusLabels,
                'issueStatusCounts' => $issueStatusCounts,

                'backupStatusLabels' => $backupStatusLabels,
                'backupStatusCounts' => $backupStatusCounts,

                'latestIssueReports' => IssueReport::with(['parkingLocation', 'reporter', 'assignedTechnician'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'latestTrafficReports' => DailyTrafficReport::with(['parkingLocation', 'user'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'latestBackupRequests' => BackupRequest::with(['requester', 'parkingLocation', 'backupItem'])
                    ->latest()
                    ->limit(5)
                    ->get(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Admin Operasional
        |--------------------------------------------------------------------------
        | Admin Operasional fokus pada master data, stok barang,
        | dan proses/complete backup yang sudah disetujui Manajer.
        */
        if ($role === 'admin') {
            $backupStatusLabels = [
                'Menunggu Verifikasi',
                'Disetujui',
                'Ditolak',
                'Dalam Proses',
                'Selesai',
            ];

            $backupStatusCounts = [];

            foreach ($backupStatusLabels as $status) {
                $backupStatusCounts[] = BackupRequest::where('status', $status)->count();
            }

            $totalStockBackupItems = BackupItem::sum('stock');

            $emptyStockItems = BackupItem::where('stock', '<=', 0)->count();

            $backupRequestsThisMonth = BackupRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $data = array_merge($data, [
                'totalLocations' => ParkingLocation::count(),

                'activeLocations' => ParkingLocation::where('status', 'Aktif')->count(),

                'inactiveLocations' => ParkingLocation::where('status', 'Tidak Aktif')->count(),

                'totalBackupItems' => BackupItem::count(),

                'availableBackupItems' => BackupItem::where('status', 'Tersedia')->count(),

                'unavailableBackupItems' => BackupItem::where('status', 'Tidak Tersedia')->count(),

                'totalStockBackupItems' => $totalStockBackupItems,

                'lowStockItems' => BackupItem::where('stock', '<=', 2)->count(),

                'emptyStockItems' => $emptyStockItems,

                /*
                |--------------------------------------------------------------------------
                | Backup Operational Processing
                |--------------------------------------------------------------------------
                | Admin Operasional memproses request yang sudah Disetujui,
                | lalu menyelesaikan request yang Dalam Proses.
                */
                'backupRequestsWaiting' => BackupRequest::where('status', 'Menunggu Verifikasi')->count(),

                'backupRequestsApproved' => BackupRequest::where('status', 'Disetujui')->count(),

                'backupRequestsProcess' => BackupRequest::where('status', 'Dalam Proses')->count(),

                'backupRequestsDone' => BackupRequest::where('status', 'Selesai')->count(),

                'backupRequestsThisMonth' => $backupRequestsThisMonth,

                'backupStatusLabels' => $backupStatusLabels,

                'backupStatusCounts' => $backupStatusCounts,

                'latestBackupRequests' => BackupRequest::with(['requester', 'parkingLocation', 'backupItem'])
                    ->latest()
                    ->limit(5)
                    ->get(),

                'approvedBackupRequests' => BackupRequest::with(['requester', 'parkingLocation', 'backupItem'])
                    ->where('status', 'Disetujui')
                    ->latest()
                    ->limit(5)
                    ->get(),

                'processBackupRequests' => BackupRequest::with(['requester', 'parkingLocation', 'backupItem'])
                    ->where('status', 'Dalam Proses')
                    ->latest()
                    ->limit(5)
                    ->get(),

                'lowStockBackupItems' => BackupItem::where('stock', '<=', 2)
                    ->orderBy('stock')
                    ->limit(5)
                    ->get(),
            ]);
        }

        return view('dashboard.index', $data);
    }
}