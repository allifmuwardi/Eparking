@extends('layouts.app')

@section('title', 'Laporan Rekap Operasional | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Laporan Rekap Operasional')
@section('page_subtitle', 'Rekap laporan kendala, traffic harian, dan permintaan backup barang')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-primary',
            'Selesai Ditangani', 'Selesai' => 'bg-success',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Ditutup / Diarsipkan' => 'bg-secondary',
            default => 'bg-secondary',
        };
    };

    $priorityBadgeClass = function ($priority) {
        return match ($priority) {
            'Rendah' => 'bg-success',
            'Sedang' => 'bg-primary',
            'Tinggi' => 'bg-warning text-dark',
            'Darurat' => 'bg-danger',
            default => 'bg-secondary',
        };
    };

    $startDateValue = request('start_date');
    $endDateValue = request('end_date');
    $locationValue = request('parking_location_id');
    $statusValue = request('status');

    $activeFilterCount = 0;

    if (!empty($startDateValue)) {
        $activeFilterCount++;
    }

    if (!empty($endDateValue)) {
        $activeFilterCount++;
    }

    if (!empty($locationValue)) {
        $activeFilterCount++;
    }

    if (!empty($statusValue)) {
        $activeFilterCount++;
    }
@endphp

<style>
    .page-title-local {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .page-subtitle-local {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
        line-height: 1.55;
        margin-bottom: 0;
    }

    .header-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 14px 28px rgba(13, 110, 253, 0.22);
        flex-shrink: 0;
    }

    .section-title-local {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .section-subtitle-local {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .btn-soft {
        border: 1px solid #d7e3f7;
        background: #ffffff;
        color: #071b4d;
        font-weight: 850;
    }

    .btn-soft:hover {
        background: #f3f8ff;
        border-color: #b9cbea;
        color: #0649bd;
    }

    .recap-hero {
        border-radius: 24px;
        padding: 24px;
        color: #ffffff;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 36%),
            linear-gradient(135deg, #0b3969 0%, #0649bd 55%, #0d6efd 100%);
        box-shadow: 0 22px 50px rgba(13, 110, 253, 0.20);
        overflow: hidden;
        position: relative;
    }

    .recap-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -90px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .recap-hero-content {
        position: relative;
        z-index: 1;
    }

    .recap-hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 31px;
        flex-shrink: 0;
    }

    .recap-hero-label {
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .recap-hero-title {
        color: #ffffff;
        font-size: 24px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .recap-hero-subtitle {
        color: rgba(255, 255, 255, 0.84);
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.6;
    }

    .filter-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .export-card {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(25, 135, 84, 0.10), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .export-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: #e7f7ee;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .summary-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 30px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 0;
    }

    .summary-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .summary-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .summary-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .recap-section-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .recap-section-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf3fc;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent 36%),
            linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .recap-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .recap-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .recap-table tbody tr:hover {
        background: #f8fbff;
    }

    .table-code {
        color: #0d6efd;
        font-size: 14px;
        font-weight: 950;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .empty-state {
        padding: 50px 16px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-state-icon {
        width: 68px;
        height: 68px;
        border-radius: 23px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 16px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .recap-hero-title {
            font-size: 21px;
        }

        .summary-value {
            font-size: 25px;
        }

        .filter-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-file-earmark-bar-graph"></i>
            </div>

            <div>
                <h3 class="page-title-local">Laporan Rekap Operasional</h3>
                <p class="page-subtitle-local">
                    Monitoring laporan kendala, traffic harian, dan permintaan backup barang ELITE Parkir.
                </p>
            </div>
        </div>

        <a href="{{ route('report-recaps.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Reset Halaman
        </a>
    </div>

    <div class="recap-hero mb-4">
        <div class="recap-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="recap-hero-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <div class="recap-hero-label">Monitoring Operasional</div>
                            <div class="recap-hero-title">Rekap Data ELITE Parkir</div>
                            <p class="recap-hero-subtitle">
                                Halaman ini digunakan Manajer Operasional untuk melihat rekap kendala,
                                laporan traffic harian, dan kebutuhan backup barang berdasarkan filter periode,
                                lokasi, serta status proses.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill bg-light text-dark">
                            Filter Aktif: {{ $activeFilterCount }}
                        </span>

                        @if (!empty($startDateValue) || !empty($endDateValue))
                            <span class="badge rounded-pill bg-light text-dark">
                                Periode Dipilih
                            </span>
                        @endif

                        @if (!empty($locationValue))
                            <span class="badge rounded-pill bg-light text-dark">
                                Lokasi Dipilih
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-card mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h5 class="section-title-local">Filter Data Rekap</h5>
                <p class="section-subtitle-local">
                    Gunakan filter untuk menampilkan data berdasarkan periode, lokasi parkir, dan status.
                </p>
            </div>

            @if ($activeFilterCount > 0)
                <a href="{{ route('report-recaps.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('report-recaps.index') }}">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Tanggal Awal</label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ $startDateValue }}"
                        class="form-control"
                    >
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Tanggal Akhir</label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ $endDateValue }}"
                        class="form-control"
                    >
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Lokasi Parkir</label>
                    <select name="parking_location_id" class="form-select">
                        <option value="">Semua Lokasi</option>

                        @foreach ($locations as $location)
                            <option
                                value="{{ $location->id }}"
                                {{ (string) $locationValue === (string) $location->id ? 'selected' : '' }}
                            >
                                {{ $location->location_name }}
                                @if (!empty($location->location_code))
                                    ({{ $location->location_code }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>

                        <optgroup label="Status Laporan Kendala">
                            @foreach ($issueStatuses as $item)
                                <option value="{{ $item }}" {{ $statusValue === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Status Permintaan Backup">
                            @foreach ($backupStatuses as $item)
                                <option value="{{ $item }}" {{ $statusValue === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-funnel me-1"></i>
                            Terapkan Filter
                        </button>

                        <a href="{{ route('report-recaps.index') }}" class="btn btn-soft rounded-3 px-4">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="export-card mt-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="export-icon">
                        <i class="bi bi-file-earmark-excel"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-success mb-1">Export Laporan Excel</div>
                        <div class="text-muted small fw-semibold">
                            Export mengikuti filter yang sedang aktif pada halaman ini.
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('report-recaps.export.issue-reports', request()->query()) }}" class="btn btn-success rounded-3">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Kendala
                    </a>

                    <a href="{{ route('report-recaps.export.traffic-reports', request()->query()) }}" class="btn btn-success rounded-3">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Traffic
                    </a>

                    <a href="{{ route('report-recaps.export.backup-requests', request()->query()) }}" class="btn btn-success rounded-3">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Backup
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Laporan Kendala</div>
                        <h4 class="summary-value text-primary">{{ number_format($totalIssueReports ?? 0) }}</h4>
                        <div class="summary-help">Total sesuai filter aktif</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Traffic Harian</div>
                        <h4 class="summary-value text-info">{{ number_format($totalTrafficReports ?? 0) }}</h4>
                        <div class="summary-help">Total laporan traffic</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-car-front"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Permintaan Backup</div>
                        <h4 class="summary-value text-warning">{{ number_format($totalBackupRequests ?? 0) }}</h4>
                        <div class="summary-help">Total request barang</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Filter Aktif</div>
                        <h4 class="summary-value text-success">{{ number_format($activeFilterCount) }}</h4>
                        <div class="summary-help">Parameter laporan</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-funnel"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Laporan Kendala --}}
    <div class="recap-section-card mb-4">
        <div class="recap-section-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h5 class="section-title-local">Rekap Laporan Kendala</h5>
                    <p class="section-subtitle-local">
                        Menampilkan data laporan kendala berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.issue-reports', request()->query()) }}" class="btn btn-outline-success rounded-3">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table recap-table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>No Laporan</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($issueReports as $index => $report)
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>

                                <td>
                                    <div class="table-code">
                                        {{ $report->report_number ?? $report->report_code ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        NIK: {{ $report->reporter->username ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $report->parkingLocation->location_name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        Kode: {{ $report->parkingLocation->location_code ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $priorityBadgeClass($report->priority ?? '') }}">
                                        {{ $report->priority ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $statusBadgeClass($report->status ?? '') }}">
                                        {{ $report->status ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $report->created_at?->format('d M Y') ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        {{ $report->created_at?->format('H:i') ?? '-' }} WIB
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>

                                        <h6 class="fw-bold mb-1 text-dark">Belum ada laporan kendala</h6>
                                        <p class="mb-0">Belum ada data laporan kendala sesuai filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekap Traffic Harian --}}
    <div class="recap-section-card mb-4">
        <div class="recap-section-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h5 class="section-title-local">Rekap Traffic Harian</h5>
                    <p class="section-subtitle-local">
                        Menampilkan data traffic harian berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.traffic-reports', request()->query()) }}" class="btn btn-outline-success rounded-3">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table recap-table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th class="text-end">Masuk</th>
                            <th class="text-end">Keluar</th>
                            <th class="text-end">Total Kendaraan</th>
                            <th class="text-end">Transaksi</th>
                            <th class="text-end">Pendapatan</th>
                            <th class="text-end" style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($trafficReports as $index => $traffic)
                            @php
                                $vehicleIn = $traffic->total_vehicle_in ?? 0;
                                $vehicleOut = $traffic->total_vehicle_out ?? 0;

                                $totalVehicle =
                                    ($traffic->car_count ?? 0)
                                    + ($traffic->motorcycle_count ?? 0)
                                    + ($traffic->other_vehicle_count ?? 0);

                                $income = $traffic->total_revenue ?? 0;

                                $petugasName = $traffic->user->full_name
                                    ?? $traffic->user->name
                                    ?? '-';
                            @endphp

                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $petugasName }}
                                    </div>

                                    <div class="muted-small">
                                        NIK: {{ $traffic->user->username ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $traffic->parkingLocation->location_name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        Kode: {{ $traffic->parkingLocation->location_code ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $traffic->report_date ? \Carbon\Carbon::parse($traffic->report_date)->format('d M Y') : '-' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $traffic->shift ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">
                                        {{ number_format($vehicleIn) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">
                                        {{ number_format($vehicleOut) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">
                                        {{ number_format($totalVehicle) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">
                                        {{ number_format($traffic->total_transaction ?? 0) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($income, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a
                                        href="{{ route('traffic-reports.show', ['trafficReport' => $traffic, 'from' => 'recap'] + request()->query()) }}"
                                        class="btn btn-sm btn-outline-primary rounded-3"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>

                                        <h6 class="fw-bold mb-1 text-dark">Belum ada traffic harian</h6>
                                        <p class="mb-0">Belum ada data traffic harian sesuai filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekap Permintaan Backup --}}
    <div class="recap-section-card mb-4">
        <div class="recap-section-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h5 class="section-title-local">Rekap Permintaan Backup</h5>
                    <p class="section-subtitle-local">
                        Menampilkan data permintaan backup barang berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.backup-requests', request()->query()) }}" class="btn btn-outline-success rounded-3">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table recap-table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Barang</th>
                            <th class="text-end">Qty</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($backupRequests as $index => $backup)
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $backup->requester->full_name ?? $backup->requester->name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        NIK: {{ $backup->requester->username ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $backup->parkingLocation->location_name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        Kode: {{ $backup->parkingLocation->location_code ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $backup->backupItem->item_name ?? '-' }}
                                    </div>

                                    <div class="muted-small">
                                        Kode: {{ $backup->backupItem->item_code ?? '-' }}
                                    </div>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">
                                        {{ number_format($backup->quantity ?? 0) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $priorityBadgeClass($backup->priority ?? '') }}">
                                        {{ $backup->priority ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $statusBadgeClass($backup->status ?? '') }}">
                                        {{ $backup->status ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>

                                        <h6 class="fw-bold mb-1 text-dark">Belum ada permintaan backup</h6>
                                        <p class="mb-0">Belum ada data permintaan backup sesuai filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection