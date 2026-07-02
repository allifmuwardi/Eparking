@extends('layouts.app')

@section('title', 'Dashboard | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $roleLabel = match ($role) {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        'manajer' => 'Manajer Operasional',
        'admin' => 'Admin Operasional',
        default => 'Pengguna',
    };

    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-secondary',
            'Selesai Ditangani' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Ditutup / Diarsipkan' => 'bg-dark',
            'Disetujui' => 'bg-success',
            'Selesai' => 'bg-primary',
            default => 'bg-secondary',
        };
    };
@endphp

<style>
    .dashboard-header {
        margin-bottom: 24px;
    }

    .dashboard-title {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .dashboard-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .dashboard-subtitle .role-text {
        color: #0d6efd;
        font-weight: 900;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        min-height: 124px;
        transition: all 0.18s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-label {
        color: #5f719a;
        font-size: 13px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 30px;
        line-height: 1;
        font-weight: 950;
        margin-bottom: 8px;
    }

    .stat-desc {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 600;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .stat-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .stat-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .stat-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .stat-icon.danger {
        background: #fde8e8;
        color: #dc3545;
    }

    .stat-icon.secondary {
        background: #eef2f7;
        color: #64748b;
    }

    .quick-action {
        display: block;
        height: 100%;
        color: inherit;
    }

    .quick-action-card {
        min-height: 100px;
        transition: all 0.18s ease;
    }

    .quick-action-card:hover {
        transform: translateY(-2px);
        border-color: #b9cbea;
    }

    .quick-icon {
        width: 56px;
        height: 56px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 27px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.18);
    }

    .quick-icon.primary {
        background: linear-gradient(135deg, #1f6de2, #0649bd);
    }

    .quick-icon.success {
        background: linear-gradient(135deg, #198754, #08713f);
    }

    .quick-icon.warning {
        background: linear-gradient(135deg, #ffc107, #ef9f00);
    }

    .section-title {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        color: #071b4d;
        font-size: 13px;
        font-weight: 900;
        border-bottom: 1px solid #d7e3f7;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .table tbody td {
        color: #263b66;
        font-size: 13px;
        font-weight: 600;
        border-bottom: 1px solid #edf2fb;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .empty-state {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 650;
        padding: 34px 10px;
        text-align: center;
    }

    .btn-soft-primary {
        border: 1px solid #b9cbea;
        background: #f8fbff;
        color: #0d6efd;
        font-weight: 850;
        border-radius: 12px;
    }

    .btn-soft-primary:hover {
        background: #0d6efd;
        color: #ffffff;
        border-color: #0d6efd;
    }

    .info-panel {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .info-panel-label {
        color: #5f719a;
        font-size: 13px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .info-panel-value {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        margin-bottom: 0;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="dashboard-header">
        <h3 class="dashboard-title">Dashboard</h3>
        <p class="dashboard-subtitle">
            Selamat datang, <span class="fw-bold">{{ $user->full_name ?? $user->name }}</span>.
            Anda login sebagai <span class="role-text">{{ $roleLabel }}</span>.
        </p>
    </div>

    {{-- PETUGAS PARKIR --}}
    @if ($role === 'petugas')
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Laporan Kendala Saya</div>
                            <div class="stat-value text-primary">{{ $myIssueReports ?? 0 }}</div>
                            <div class="stat-desc">Total laporan dibuat</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Menunggu Verifikasi</div>
                            <div class="stat-value text-warning">{{ $myPendingReports ?? 0 }}</div>
                            <div class="stat-desc">Menunggu Manajer</div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Dalam Proses</div>
                            <div class="stat-value text-info">{{ $myProcessReports ?? 0 }}</div>
                            <div class="stat-desc">Sedang ditangani teknisi</div>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Permintaan Backup</div>
                            <div class="stat-value text-success">{{ $myBackupRequests ?? 0 }}</div>
                            <div class="stat-desc">Total permintaan backup</div>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <a href="{{ route('issue-reports.create') }}" class="quick-action">
                    <div class="page-card quick-action-card p-4 h-100">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="quick-icon primary">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Buat Laporan Kendala</h5>
                                <p class="text-muted mb-0 small">Laporkan kendala parkir yang terjadi di lokasi.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="{{ route('traffic-reports.create') }}" class="quick-action">
                    <div class="page-card quick-action-card p-4 h-100">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="quick-icon success">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Input Traffic Harian</h5>
                                <p class="text-muted mb-0 small">Catat data kendaraan dan transaksi harian.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="{{ route('backup-requests.create') }}" class="quick-action">
                    <div class="page-card quick-action-card p-4 h-100">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="quick-icon warning">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Ajukan Backup Barang</h5>
                                <p class="text-muted mb-0 small">Ajukan kebutuhan barang backup operasional.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Laporan Kendala Terbaru</h5>

                    <div class="table-responsive mt-3">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No. Laporan</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($latestIssueReports ?? collect()) as $report)
                                    <tr>
                                        <td>
                                            <a href="{{ route('issue-reports.show', $report) }}" class="fw-bold text-primary">
                                                {{ $report->report_number ?? '-' }}
                                            </a>
                                            <div class="text-muted small">{{ $report->title ?? '-' }}</div>
                                        </td>
                                        <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $statusBadgeClass($report->status ?? '') }}">
                                                {{ $report->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            Belum ada laporan kendala.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Traffic Harian Terbaru</h5>

                    <div class="table-responsive mt-3">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Shift</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($latestTrafficReports ?? collect()) as $traffic)
                                    <tr>
                                        <td>{{ $traffic->report_date?->format('d M Y') ?? $traffic->report_date ?? '-' }}</td>
                                        <td>{{ $traffic->parkingLocation->location_name ?? '-' }}</td>
                                        <td>{{ $traffic->shift ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">
                                            Belum ada traffic harian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TEKNISI VENDOR --}}
    @if ($role === 'teknisi')
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Total Tugas</div>
                            <div class="stat-value text-primary">{{ $assignedReports ?? 0 }}</div>
                            <div class="stat-desc">Laporan ditugaskan</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Dalam Proses</div>
                            <div class="stat-value text-info">{{ $processReports ?? 0 }}</div>
                            <div class="stat-desc">Sedang ditangani</div>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Menunggu Informasi</div>
                            <div class="stat-value text-secondary">{{ $waitingInfoReports ?? 0 }}</div>
                            <div class="stat-desc">Butuh data tambahan</div>
                        </div>
                        <div class="stat-icon secondary">
                            <i class="bi bi-info-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Selesai Ditangani</div>
                            <div class="stat-value text-success">{{ $finishedReports ?? 0 }}</div>
                            <div class="stat-desc">Menunggu penutupan Manajer</div>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-card p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="section-title">Laporan Ditugaskan Terbaru</h5>
                    <p class="section-subtitle">Daftar laporan kendala yang ditugaskan kepada Anda.</p>
                </div>

                <a href="{{ route('technician-reports.index') }}" class="btn btn-soft-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Lokasi</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (($latestAssignedReports ?? collect()) as $report)
                            <tr>
                                <td>
                                    <div class="fw-bold text-primary">{{ $report->report_number ?? '-' }}</div>
                                    <div class="text-muted small">{{ $report->title ?? '-' }}</div>
                                </td>
                                <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                <td>{{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $statusBadgeClass($report->status ?? '') }}">
                                        {{ $report->status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $report->created_at?->format('d M Y') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('technician-reports.show', $report) }}" class="btn btn-sm btn-soft-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada laporan yang ditugaskan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- MANAJER OPERASIONAL --}}
    @if ($role === 'manajer')
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Total Laporan Kendala</div>
                    <div class="stat-value text-primary">{{ $totalIssueReports ?? 0 }}</div>
                    <div class="stat-desc">Seluruh laporan masuk</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Menunggu Verifikasi</div>
                    <div class="stat-value text-warning">{{ $waitingVerificationReports ?? 0 }}</div>
                    <div class="stat-desc">Perlu approve / reject</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Backup Menunggu Approval</div>
                    <div class="stat-value text-danger">{{ $backupRequestsWaiting ?? 0 }}</div>
                    <div class="stat-desc">Permintaan backup baru</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Traffic Bulan Ini</div>
                    <div class="stat-value text-success">{{ $trafficReportsThisMonth ?? 0 }}</div>
                    <div class="stat-desc">Laporan traffic masuk</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Grafik Status Laporan Kendala</h5>
                    <canvas id="issueStatusChart" height="180" class="mt-3"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Grafik Status Permintaan Backup</h5>
                    <canvas id="backupStatusChart" height="180" class="mt-3"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Total Kendaraan Bulan Ini</div>
                    <h4 class="fw-bold mb-0">{{ number_format($totalVehiclesThisMonth ?? 0) }}</h4>
                    <div class="stat-desc mt-2">Berdasarkan laporan traffic</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Pendapatan Bulan Ini</div>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($totalTrafficIncomeThisMonth ?? 0, 0, ',', '.') }}</h4>
                    <div class="stat-desc mt-2">Berdasarkan laporan traffic</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Backup Bulan Ini</div>
                    <h4 class="fw-bold mb-0">{{ $backupRequestsThisMonth ?? 0 }}</h4>
                    <div class="stat-desc mt-2">Total permintaan backup</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="section-title">Laporan Kendala Terbaru</h5>
                            <p class="section-subtitle">Pantau laporan terbaru dari Petugas Parkir.</p>
                        </div>

                        <a href="{{ route('manage-reports.index') }}" class="btn btn-soft-primary">
                            Manage Report
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No. Laporan</th>
                                    <th>Lokasi</th>
                                    <th>Petugas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($latestIssueReports ?? collect()) as $report)
                                    <tr>
                                        <td>
                                            <a href="{{ route('manage-reports.show', $report) }}" class="fw-bold text-primary">
                                                {{ $report->report_number ?? '-' }}
                                            </a>
                                            <div class="text-muted small">{{ $report->title ?? '-' }}</div>
                                        </td>
                                        <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                        <td>{{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $statusBadgeClass($report->status ?? '') }}">
                                                {{ $report->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            Belum ada laporan kendala.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="page-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="section-title">Permintaan Backup Terbaru</h5>
                            <p class="section-subtitle">Manajer approve/reject permintaan backup.</p>
                        </div>

                        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft-primary">
                            Lihat
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Barang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($latestBackupRequests ?? collect()) as $request)
                                    <tr>
                                        <td>
                                            <a href="{{ route('backup-requests.show', $request) }}" class="fw-bold text-primary">
                                                {{ $request->request_number ?? '-' }}
                                            </a>
                                            <div class="text-muted small">
                                                {{ $request->requester->full_name ?? $request->requester->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>{{ $request->backupItem->item_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $statusBadgeClass($request->status ?? '') }}">
                                                {{ $request->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">
                                            Belum ada permintaan backup.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('report-recaps.index') }}" class="btn btn-primary rounded-3 w-100">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i>
                            Buka Laporan Rekap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ADMIN OPERASIONAL --}}
    @if ($role === 'admin')
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Total Lokasi Parkir</div>
                    <div class="stat-value text-primary">{{ $totalLocations ?? 0 }}</div>
                    <div class="stat-desc">Master lokasi</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Total Barang Backup</div>
                    <div class="stat-value text-success">{{ $totalBackupItems ?? 0 }}</div>
                    <div class="stat-desc">Master barang</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Siap Diproses</div>
                    <div class="stat-value text-warning">{{ $backupRequestsApproved ?? 0 }}</div>
                    <div class="stat-desc">Backup sudah disetujui</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-card stat-card p-4 h-100">
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value text-danger">{{ $lowStockItems ?? 0 }}</div>
                    <div class="stat-desc">Stok kurang/sama dengan 2</div>
                </div>
            </div>
        </div>

        <div class="alert alert-info rounded-4 border-0 mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Alur Admin Operasional
            </div>
            Admin Operasional mengelola master lokasi, master barang backup, stok barang, dan memproses permintaan backup yang sudah disetujui Manajer Operasional.
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Status Permintaan Backup</h5>
                    <canvas id="adminBackupStatusChart" height="180" class="mt-3"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Ringkasan Master Data</h5>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="info-panel h-100">
                                <div class="info-panel-label">Lokasi Aktif</div>
                                <h4 class="info-panel-value text-success">{{ $activeLocations ?? 0 }}</h4>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-panel h-100">
                                <div class="info-panel-label">Lokasi Tidak Aktif</div>
                                <h4 class="info-panel-value text-secondary">{{ $inactiveLocations ?? 0 }}</h4>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-panel h-100">
                                <div class="info-panel-label">Total Stok Barang</div>
                                <h4 class="info-panel-value text-primary">{{ number_format($totalStockBackupItems ?? 0) }}</h4>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-panel h-100">
                                <div class="info-panel-label">Stok Kosong</div>
                                <h4 class="info-panel-value text-danger">{{ $emptyStockItems ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('parking-locations.index') }}" class="btn btn-soft-primary w-100">
                            Master Lokasi
                        </a>
                        <a href="{{ route('backup-items.index') }}" class="btn btn-primary rounded-3 w-100">
                            Master Barang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="section-title">Backup Siap Diproses</h5>
                            <p class="section-subtitle">
                                Permintaan backup yang sudah disetujui oleh Manajer Operasional.
                            </p>
                        </div>

                        <a href="{{ route('backup-requests.index', ['status' => 'Disetujui']) }}" class="btn btn-soft-primary">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No. Request</th>
                                    <th>Barang</th>
                                    <th>Lokasi</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($approvedBackupRequests ?? collect()) as $request)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $request->request_number ?? '-' }}</div>
                                            <div class="text-muted small">{{ $request->requester->full_name ?? $request->requester->name ?? '-' }}</div>
                                        </td>
                                        <td>{{ $request->backupItem->item_name ?? '-' }}</td>
                                        <td>{{ $request->parkingLocation->location_name ?? '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('backup-requests.show', $request) }}" class="btn btn-sm btn-soft-primary">
                                                Proses
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            Belum ada permintaan backup yang siap diproses.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="page-card p-4 h-100">
                    <h5 class="section-title">Barang Stok Menipis</h5>

                    <div class="table-responsive mt-3">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($lowStockBackupItems ?? collect()) as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('backup-items.show', $item) }}" class="fw-bold text-primary">
                                                {{ $item->item_name ?? '-' }}
                                            </a>
                                            <div class="text-muted small">{{ $item->item_code ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ ($item->stock ?? 0) <= 0 ? 'text-danger' : 'text-warning' }}">
                                                {{ number_format($item->stock ?? 0) }} {{ $item->unit ?? '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ ($item->status ?? '') === 'Tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">
                                            Tidak ada barang dengan stok menipis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('backup-items.index') }}" class="btn btn-primary rounded-3 w-100">
                            Kelola Master Barang Backup
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
    @if ($role === 'manajer' || $role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            @if ($role === 'manajer')
                const issueStatusChartElement = document.getElementById('issueStatusChart');

                if (issueStatusChartElement) {
                    new Chart(issueStatusChartElement, {
                        type: 'bar',
                        data: {
                            labels: @json($issueStatusLabels ?? []),
                            datasets: [{
                                label: 'Jumlah Laporan',
                                data: @json($issueStatusCounts ?? []),
                                backgroundColor: [
                                    '#ffc107',
                                    '#0dcaf0',
                                    '#6c757d',
                                    '#198754',
                                    '#dc3545',
                                    '#212529'
                                ],
                                borderWidth: 0,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }

                const backupStatusChartElement = document.getElementById('backupStatusChart');

                if (backupStatusChartElement) {
                    new Chart(backupStatusChartElement, {
                        type: 'doughnut',
                        data: {
                            labels: @json($backupStatusLabels ?? []),
                            datasets: [{
                                data: @json($backupStatusCounts ?? []),
                                backgroundColor: [
                                    '#ffc107',
                                    '#198754',
                                    '#dc3545',
                                    '#0dcaf0',
                                    '#0d6efd'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            @endif

            @if ($role === 'admin')
                const adminBackupStatusChartElement = document.getElementById('adminBackupStatusChart');

                if (adminBackupStatusChartElement) {
                    new Chart(adminBackupStatusChartElement, {
                        type: 'doughnut',
                        data: {
                            labels: @json($backupStatusLabels ?? []),
                            datasets: [{
                                data: @json($backupStatusCounts ?? []),
                                backgroundColor: [
                                    '#ffc107',
                                    '#198754',
                                    '#dc3545',
                                    '#0dcaf0',
                                    '#0d6efd'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            @endif
        </script>
    @endif
@endpush