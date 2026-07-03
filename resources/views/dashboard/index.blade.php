@extends('layouts.app')

@section('title', 'Dashboard | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan aktivitas operasional parkir')

@section('content')
@php
    $roleLabel = match ($role ?? '') {
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
            'Diproses' => 'bg-info text-dark',
            'Selesai' => 'bg-primary',
            'Dibatalkan' => 'bg-danger',
            default => 'bg-secondary',
        };
    };

    $displayName = $user->full_name ?? $user->name ?? 'Pengguna';
    $locationName = $user->parkingLocation->location_name ?? null;
@endphp

<style>
    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.35), transparent 34%),
            linear-gradient(135deg, #0b3969 0%, #0649bd 55%, #0d6efd 100%);
        color: #ffffff;
        box-shadow: 0 22px 50px rgba(13, 110, 253, 0.22);
        margin-bottom: 24px;
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -74px;
        bottom: -92px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        color: rgba(255, 255, 255, 0.95);
        font-size: 12px;
        font-weight: 850;
        margin-bottom: 14px;
    }

    .hero-title {
        font-size: 30px;
        font-weight: 950;
        letter-spacing: -0.5px;
        margin-bottom: 7px;
    }

    .hero-subtitle {
        max-width: 760px;
        color: rgba(255, 255, 255, 0.82);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 0;
        line-height: 1.65;
    }

    .hero-mini-card {
        border-radius: 18px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(12px);
    }

    .hero-mini-label {
        color: rgba(255, 255, 255, 0.75);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .hero-mini-value {
        color: #ffffff;
        font-size: 14px;
        font-weight: 900;
        margin-bottom: 0;
    }

    .stat-card {
        position: relative;
        min-height: 138px;
        overflow: hidden;
        transition: all 0.18s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: #b9cbea;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 92px;
        height: 92px;
        right: -34px;
        bottom: -34px;
        border-radius: 999px;
        background: rgba(13, 110, 253, 0.07);
    }

    .stat-label {
        color: #5f719a;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 33px;
        line-height: 1;
        font-weight: 950;
        margin-bottom: 9px;
        letter-spacing: -0.5px;
    }

    .stat-desc {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        line-height: 1.45;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
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
        color: inherit;
        height: 100%;
    }

    .quick-action-card {
        min-height: 116px;
        transition: all 0.18s ease;
        overflow: hidden;
        position: relative;
    }

    .quick-action-card:hover {
        transform: translateY(-3px);
        border-color: #b9cbea;
    }

    .quick-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 28px;
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

    .quick-title {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 5px;
    }

    .quick-desc {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.5;
    }

    .dashboard-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 18px;
    }

    .section-title {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 4px;
        letter-spacing: -0.2px;
    }

    .section-subtitle {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.5;
    }

    .empty-state {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 650;
        padding: 38px 10px;
        text-align: center;
    }

    .empty-state i {
        display: block;
        color: #b8c7de;
        font-size: 34px;
        margin-bottom: 10px;
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
        font-weight: 800;
        margin-bottom: 7px;
    }

    .info-panel-value {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        margin-bottom: 0;
    }

    .table-link-title {
        font-weight: 900;
        color: #0d6efd;
    }

    .table-link-title:hover {
        color: #0649bd;
    }

    .table-subtext {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 3px;
    }

    .chart-wrap {
        min-height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 22px;
        }

        .hero-title {
            font-size: 23px;
        }

        .hero-subtitle {
            font-size: 13px;
        }

        .stat-value {
            font-size: 28px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="dashboard-hero">
        <div class="hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="hero-kicker">
                        <i class="bi bi-p-square-fill"></i>
                        Sistem Penanganan Kendala Parkir
                    </div>

                    <h3 class="hero-title">
                        Selamat datang, {{ $displayName }}
                    </h3>

                    <p class="hero-subtitle">
                        Anda login sebagai <strong>{{ $roleLabel }}</strong>.
                        Gunakan dashboard ini untuk memantau aktivitas pelaporan kendala, traffic harian,
                        permintaan backup barang, dan proses tindak lanjut operasional parkir.
                    </p>
                </div>

                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="hero-mini-card">
                                <div class="hero-mini-label">Role</div>
                                <p class="hero-mini-value">{{ $roleLabel }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="hero-mini-card">
                                <div class="hero-mini-label">Status</div>
                                <p class="hero-mini-value">Aktif</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hero-mini-card">
                                <div class="hero-mini-label">Lokasi Operasional</div>
                                <p class="hero-mini-value">{{ $locationName ?? 'Seluruh Lokasi / Sesuai Akses' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PETUGAS PARKIR --}}
    @if ($role === 'petugas')
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Laporan Kendala</div>
                            <div class="stat-value text-primary">{{ $myIssueReports ?? 0 }}</div>
                            <div class="stat-desc">Total laporan yang dibuat</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Menunggu Verifikasi</div>
                            <div class="stat-value text-warning">{{ $myPendingReports ?? 0 }}</div>
                            <div class="stat-desc">Menunggu pemeriksaan Manajer</div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Dalam Proses</div>
                            <div class="stat-value text-info">{{ $myProcessReports ?? 0 }}</div>
                            <div class="stat-desc">Sedang ditangani Teknisi</div>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Permintaan Backup</div>
                            <div class="stat-value text-success">{{ $myBackupRequests ?? 0 }}</div>
                            <div class="stat-desc">Total pengajuan barang backup</div>
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
                                <h5 class="quick-title">Buat Laporan Kendala</h5>
                                <p class="quick-desc">Laporkan kendala parkir yang terjadi di lokasi operasional.</p>
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
                                <h5 class="quick-title">Input Traffic Harian</h5>
                                <p class="quick-desc">Catat kendaraan masuk, keluar, transaksi, dan pendapatan.</p>
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
                                <h5 class="quick-title">Ajukan Backup Barang</h5>
                                <p class="quick-desc">Ajukan kebutuhan barang backup untuk operasional parkir.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Laporan Kendala Terbaru</h5>
                            <p class="section-subtitle">Riwayat laporan terbaru berdasarkan lokasi operasional Anda.</p>
                        </div>

                        <a href="{{ route('issue-reports.index') }}" class="btn btn-soft-primary">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
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
                                            <a href="{{ route('issue-reports.show', $report) }}" class="table-link-title">
                                                {{ $report->report_number ?? '-' }}
                                            </a>
                                            <div class="table-subtext">{{ $report->title ?? '-' }}</div>
                                        </td>
                                        <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $statusBadgeClass($report->status ?? '') }}">
                                                {{ $report->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at?->format('d M Y H:i') ?? '-' }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <i class="bi bi-inbox"></i>
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
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Traffic Harian Terbaru</h5>
                            <p class="section-subtitle">Data traffic terbaru dari lokasi operasional Anda.</p>
                        </div>

                        <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft-primary">
                            Lihat
                        </a>
                    </div>

                    <div class="table-responsive">
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
                                            <i class="bi bi-bar-chart"></i>
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
            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Total Tugas</div>
                            <div class="stat-value text-primary">{{ $assignedReports ?? 0 }}</div>
                            <div class="stat-desc">Laporan yang ditugaskan</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Dalam Proses</div>
                            <div class="stat-value text-info">{{ $processReports ?? 0 }}</div>
                            <div class="stat-desc">Sedang Anda tangani</div>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Menunggu Informasi</div>
                            <div class="stat-value text-secondary">{{ $waitingInfoReports ?? 0 }}</div>
                            <div class="stat-desc">Membutuhkan data tambahan</div>
                        </div>
                        <div class="stat-icon secondary">
                            <i class="bi bi-info-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
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
            <div class="dashboard-section-header">
                <div>
                    <h5 class="section-title">Laporan Ditugaskan Terbaru</h5>
                    <p class="section-subtitle">Daftar laporan kendala yang perlu ditindaklanjuti oleh Teknisi Vendor.</p>
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
                                    <div class="table-link-title">{{ $report->report_number ?? '-' }}</div>
                                    <div class="table-subtext">{{ $report->title ?? '-' }}</div>
                                </td>
                                <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                <td>{{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $statusBadgeClass($report->status ?? '') }}">
                                        {{ $report->status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $report->created_at?->format('d M Y H:i') ?? '-' }} WIB</td>
                                <td class="text-end">
                                    <a href="{{ route('technician-reports.show', $report) }}" class="btn btn-sm btn-soft-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="bi bi-clipboard-x"></i>
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
            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Total Laporan Kendala</div>
                            <div class="stat-value text-primary">{{ $totalIssueReports ?? 0 }}</div>
                            <div class="stat-desc">Seluruh laporan yang masuk</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Menunggu Verifikasi</div>
                            <div class="stat-value text-warning">{{ $waitingVerificationReports ?? 0 }}</div>
                            <div class="stat-desc">Perlu approve atau reject</div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Backup Waiting</div>
                            <div class="stat-value text-danger">{{ $backupRequestsWaiting ?? 0 }}</div>
                            <div class="stat-desc">Permintaan backup baru</div>
                        </div>
                        <div class="stat-icon danger">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Traffic Bulan Ini</div>
                            <div class="stat-value text-success">{{ $trafficReportsThisMonth ?? 0 }}</div>
                            <div class="stat-desc">Laporan traffic masuk</div>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Grafik Status Laporan Kendala</h5>
                            <p class="section-subtitle">Ringkasan status laporan kendala operasional.</p>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <canvas id="issueStatusChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Grafik Status Permintaan Backup</h5>
                            <p class="section-subtitle">Ringkasan status pengajuan barang backup.</p>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <canvas id="backupStatusChart" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Total Kendaraan Bulan Ini</div>
                    <h4 class="fw-bold mb-0 text-primary">{{ number_format($totalVehiclesThisMonth ?? 0) }}</h4>
                    <div class="stat-desc mt-2">Berdasarkan laporan traffic harian.</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Pendapatan Bulan Ini</div>
                    <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($totalTrafficIncomeThisMonth ?? 0, 0, ',', '.') }}</h4>
                    <div class="stat-desc mt-2">Akumulasi pendapatan dari laporan traffic.</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="page-card p-4 h-100">
                    <div class="stat-label">Backup Bulan Ini</div>
                    <h4 class="fw-bold mb-0 text-warning">{{ $backupRequestsThisMonth ?? 0 }}</h4>
                    <div class="stat-desc mt-2">Total permintaan backup barang.</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
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
                                            <a href="{{ route('manage-reports.show', $report) }}" class="table-link-title">
                                                {{ $report->report_number ?? '-' }}
                                            </a>
                                            <div class="table-subtext">{{ $report->title ?? '-' }}</div>
                                        </td>
                                        <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                                        <td>{{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $statusBadgeClass($report->status ?? '') }}">
                                                {{ $report->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <i class="bi bi-inbox"></i>
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
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Permintaan Backup Terbaru</h5>
                            <p class="section-subtitle">Permintaan backup yang membutuhkan monitoring Manajer.</p>
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
                                            <a href="{{ route('backup-requests.show', $request) }}" class="table-link-title">
                                                {{ $request->request_number ?? '-' }}
                                            </a>
                                            <div class="table-subtext">
                                                {{ $request->requester->full_name ?? $request->requester->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>{{ $request->backupItem->item_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $statusBadgeClass($request->status ?? '') }}">
                                                {{ $request->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">
                                            <i class="bi bi-box"></i>
                                            Belum ada permintaan backup.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('report-recaps.index') }}" class="btn btn-primary w-100">
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
            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Total Lokasi Parkir</div>
                            <div class="stat-value text-primary">{{ $totalLocations ?? 0 }}</div>
                            <div class="stat-desc">Data master lokasi operasional</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Total Barang Backup</div>
                            <div class="stat-value text-success">{{ $totalBackupItems ?? 0 }}</div>
                            <div class="stat-desc">Data master barang backup</div>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Siap Diproses</div>
                            <div class="stat-value text-warning">{{ $backupRequestsApproved ?? 0 }}</div>
                            <div class="stat-desc">Backup sudah disetujui Manajer</div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-check2-square"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="page-card stat-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="stat-label">Stok Menipis</div>
                            <div class="stat-value text-danger">{{ $lowStockItems ?? 0 }}</div>
                            <div class="stat-desc">Barang dengan stok rendah</div>
                        </div>
                        <div class="stat-icon danger">
                            <i class="bi bi-exclamation-octagon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-card p-4 mb-4">
            <div class="d-flex gap-3 align-items-start">
                <div class="stat-icon primary">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div>
                    <h5 class="section-title mb-1">Alur Admin Operasional</h5>
                    <p class="section-subtitle mb-0">
                        Admin Operasional mengelola master lokasi, master barang backup, stok barang,
                        serta memproses permintaan backup yang sudah disetujui oleh Manajer Operasional.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Status Permintaan Backup</h5>
                            <p class="section-subtitle">Ringkasan status permintaan backup barang.</p>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <canvas id="adminBackupStatusChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Ringkasan Master Data</h5>
                            <p class="section-subtitle">Informasi kondisi data lokasi dan stok barang.</p>
                        </div>
                    </div>

                    <div class="row g-3">
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

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <a href="{{ route('parking-locations.index') }}" class="btn btn-soft-primary flex-fill">
                            Master Lokasi
                        </a>
                        <a href="{{ route('backup-items.index') }}" class="btn btn-primary flex-fill">
                            Master Barang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="page-card p-4 h-100">
                    <div class="dashboard-section-header">
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
                                            <div class="table-link-title">{{ $request->request_number ?? '-' }}</div>
                                            <div class="table-subtext">{{ $request->requester->full_name ?? $request->requester->name ?? '-' }}</div>
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
                                            <i class="bi bi-box"></i>
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
                    <div class="dashboard-section-header">
                        <div>
                            <h5 class="section-title">Barang Stok Menipis</h5>
                            <p class="section-subtitle">Daftar barang backup yang perlu diperhatikan stoknya.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
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
                                            <a href="{{ route('backup-items.show', $item) }}" class="table-link-title">
                                                {{ $item->item_name ?? '-' }}
                                            </a>
                                            <div class="table-subtext">{{ $item->item_code ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ ($item->stock ?? 0) <= 0 ? 'text-danger' : 'text-warning' }}">
                                                {{ number_format($item->stock ?? 0) }} {{ $item->unit ?? '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ ($item->status ?? '') === 'Tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">
                                            <i class="bi bi-check-circle"></i>
                                            Tidak ada barang dengan stok menipis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('backup-items.index') }}" class="btn btn-primary w-100">
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
            const chartGridColor = 'rgba(215, 227, 247, 0.9)';
            const chartTextColor = '#5f719a';

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
                                borderRadius: 10,
                                maxBarThickness: 46
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#071b4d',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    padding: 12,
                                    cornerRadius: 10
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: chartTextColor,
                                        font: {
                                            weight: 700
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: chartGridColor
                                    },
                                    ticks: {
                                        precision: 0,
                                        color: chartTextColor,
                                        font: {
                                            weight: 700
                                        }
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
                                borderColor: '#ffffff',
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '62%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: chartTextColor,
                                        font: {
                                            weight: 700
                                        },
                                        usePointStyle: true,
                                        padding: 16
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#071b4d',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    padding: 12,
                                    cornerRadius: 10
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
                                borderColor: '#ffffff',
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '62%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: chartTextColor,
                                        font: {
                                            weight: 700
                                        },
                                        usePointStyle: true,
                                        padding: 16
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#071b4d',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    padding: 12,
                                    cornerRadius: 10
                                }
                            }
                        }
                    });
                }
            @endif
        </script>
    @endif
@endpush