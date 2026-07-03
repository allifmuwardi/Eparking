@extends('layouts.app')

@section('title', 'Detail Traffic Harian | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Traffic Harian')
@section('page_subtitle', 'Detail laporan traffic kendaraan dan transaksi harian')

@section('content')
@php
    $authUser = Auth::user();
    $currentRole = $authUser->role ?? '';

    $backToRecap = request('from') === 'recap';

    $backUrl = $backToRecap
        ? route('report-recaps.index', request()->except(['from']))
        : route('traffic-reports.index');

    $backLabel = $backToRecap
        ? 'Kembali ke Laporan Rekap'
        : 'Kembali ke Daftar Traffic Harian';

    $reporterName = $trafficReport->user->full_name
        ?? $trafficReport->user->name
        ?? '-';

    $locationName = $trafficReport->parkingLocation->location_name ?? '-';
    $locationCode = $trafficReport->parkingLocation->location_code ?? '-';

    $reportDate = $trafficReport->report_date
        ? \Carbon\Carbon::parse($trafficReport->report_date)->format('d M Y')
        : '-';

    $carCount = $trafficReport->car_count ?? 0;
    $motorcycleCount = $trafficReport->motorcycle_count ?? 0;
    $otherVehicleCount = $trafficReport->other_vehicle_count ?? 0;

    $totalVehicle = $carCount + $motorcycleCount + $otherVehicleCount;

    $vehicleIn = $trafficReport->total_vehicle_in ?? 0;
    $vehicleOut = $trafficReport->total_vehicle_out ?? 0;
    $totalTransaction = $trafficReport->total_transaction ?? 0;
    $totalRevenue = $trafficReport->total_revenue ?? 0;

    $canModify = $currentRole === 'petugas'
        && (int) ($trafficReport->user_id ?? 0) === (int) ($authUser->id ?? 0);

    $photoPath = $trafficReport->photo_path
        ?? $trafficReport->documentation_photo
        ?? $trafficReport->photo
        ?? null;
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

    .traffic-hero {
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

    .traffic-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -90px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .traffic-hero-content {
        position: relative;
        z-index: 1;
    }

    .traffic-hero-icon {
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

    .traffic-hero-label {
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .traffic-hero-title {
        color: #ffffff;
        font-size: 24px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .traffic-hero-subtitle {
        color: rgba(255, 255, 255, 0.84);
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.6;
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

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .info-box {
        height: 100%;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .info-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .info-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .vehicle-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.04);
    }

    .vehicle-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        margin-bottom: 14px;
    }

    .vehicle-icon.car {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .vehicle-icon.motorcycle {
        background: #e7f7ee;
        color: #198754;
    }

    .vehicle-icon.other {
        background: #fff6dc;
        color: #d99a00;
    }

    .vehicle-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 4px;
    }

    .vehicle-value {
        color: #071b4d;
        font-size: 30px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .note-box {
        min-height: 130px;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 18px;
        color: #071b4d;
        font-weight: 650;
        line-height: 1.7;
        white-space: pre-line;
    }

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .side-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #edf3fc;
    }

    .side-row:last-child {
        border-bottom: none;
    }

    .side-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 750;
    }

    .side-value {
        color: #071b4d;
        font-size: 13px;
        font-weight: 950;
        text-align: right;
    }

    .photo-wrapper {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 12px;
        overflow: hidden;
    }

    .photo-wrapper img {
        width: 100%;
        border-radius: 16px;
        object-fit: cover;
    }

    .empty-photo {
        border-radius: 20px;
        border: 1px dashed #b9cbea;
        background: #f8fbff;
        padding: 40px 18px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-photo-icon {
        width: 64px;
        height: 64px;
        border-radius: 22px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 31px;
        margin: 0 auto 14px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .traffic-hero-title {
            font-size: 21px;
        }

        .summary-value,
        .vehicle-value {
            font-size: 25px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-car-front"></i>
            </div>

            <div>
                <h3 class="page-title-local">Detail Traffic Harian</h3>
                <p class="page-subtitle-local">
                    {{ $locationName }} — {{ $reportDate }} — Shift {{ $trafficReport->shift ?? '-' }}
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($canModify && !$backToRecap)
                <a href="{{ route('traffic-reports.edit', $trafficReport) }}" class="btn btn-warning text-white rounded-3 px-3">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit
                </a>
            @endif

            <a href="{{ $backUrl }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                {{ $backLabel }}
            </a>
        </div>
    </div>

    <div class="traffic-hero mb-4">
        <div class="traffic-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="traffic-hero-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>

                        <div>
                            <div class="traffic-hero-label">Traffic Harian</div>
                            <div class="traffic-hero-title">
                                {{ $locationName }}
                            </div>

                            <p class="traffic-hero-subtitle">
                                Laporan traffic tanggal <strong>{{ $reportDate }}</strong>
                                pada shift <strong>{{ $trafficReport->shift ?? '-' }}</strong>
                                dibuat oleh <strong>{{ $reporterName }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill bg-light text-dark">
                            {{ $locationCode }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            Shift {{ $trafficReport->shift ?? '-' }}
                        </span>

                        @if ($backToRecap)
                            <span class="badge rounded-pill bg-warning text-dark">
                                Dari Laporan Rekap
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Kendaraan</div>
                        <h4 class="summary-value text-primary">{{ number_format($totalVehicle) }}</h4>
                        <div class="summary-help">Mobil + motor + lainnya</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-car-front"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Kendaraan Masuk</div>
                        <h4 class="summary-value text-info">{{ number_format($vehicleIn) }}</h4>
                        <div class="summary-help">Total vehicle in</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Transaksi</div>
                        <h4 class="summary-value text-success">{{ number_format($totalTransaction) }}</h4>
                        <div class="summary-help">Total transaksi harian</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Pendapatan</div>
                        <h4 class="summary-value text-warning">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        <div class="summary-help">Total revenue</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Laporan Traffic</h5>
                    <p class="section-subtitle-local">
                        Detail data petugas, lokasi, tanggal, dan shift laporan traffic harian.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Petugas</div>
                            <div class="info-value">{{ $reporterName }}</div>
                            <div class="info-help">NIK: {{ $trafficReport->user->username ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Lokasi Parkir</div>
                            <div class="info-value">{{ $locationName }}</div>
                            <div class="info-help">Kode: {{ $locationCode }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Tanggal Laporan</div>
                            <div class="info-value">{{ $reportDate }}</div>
                            <div class="info-help">Tanggal traffic dicatat.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Shift</div>
                            <div class="info-value">{{ $trafficReport->shift ?? '-' }}</div>
                            <div class="info-help">Shift operasional petugas.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Dibuat</div>
                            <div class="info-value">{{ $trafficReport->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                            <div class="info-help">Waktu input laporan.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Rincian Kendaraan</h5>
                    <p class="section-subtitle-local">
                        Rekap kendaraan berdasarkan jenis kendaraan yang tercatat.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="vehicle-card">
                            <div class="vehicle-icon car">
                                <i class="bi bi-car-front"></i>
                            </div>

                            <div class="vehicle-label">Mobil</div>
                            <div class="vehicle-value">{{ number_format($carCount) }}</div>
                            <div class="text-muted small fw-semibold">Jumlah kendaraan mobil.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="vehicle-card">
                            <div class="vehicle-icon motorcycle">
                                <i class="bi bi-bicycle"></i>
                            </div>

                            <div class="vehicle-label">Motor</div>
                            <div class="vehicle-value">{{ number_format($motorcycleCount) }}</div>
                            <div class="text-muted small fw-semibold">Jumlah kendaraan motor.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="vehicle-card">
                            <div class="vehicle-icon other">
                                <i class="bi bi-truck"></i>
                            </div>

                            <div class="vehicle-label">Kendaraan Lain</div>
                            <div class="vehicle-value">{{ number_format($otherVehicleCount) }}</div>
                            <div class="text-muted small fw-semibold">Jumlah kendaraan lainnya.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Catatan Laporan</h5>
                    <p class="section-subtitle-local">
                        Catatan tambahan dari Petugas Parkir pada laporan traffic harian.
                    </p>
                </div>

                <div class="note-box">
                    {{ $trafficReport->notes ?? $trafficReport->note ?? 'Tidak ada catatan tambahan.' }}
                </div>
            </div>

            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Dokumentasi Traffic</h5>
                    <p class="section-subtitle-local">
                        Foto atau dokumentasi pendukung laporan traffic harian.
                    </p>
                </div>

                @if (!empty($photoPath))
                    <div class="photo-wrapper">
                        <a href="{{ asset('storage/' . $photoPath) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photoPath) }}" alt="Dokumentasi Traffic">
                        </a>
                    </div>
                @else
                    <div class="empty-photo">
                        <div class="empty-photo-icon">
                            <i class="bi bi-image"></i>
                        </div>

                        <h6 class="fw-bold mb-1 text-dark">Tidak ada dokumentasi</h6>
                        <p class="mb-0">Laporan traffic ini belum memiliki foto dokumentasi.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="side-card mb-4 sticky-top" style="top: 120px;">
                <h5 class="section-title-local">Ringkasan Traffic</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan angka utama pada laporan traffic harian.
                </p>

                <div class="side-row">
                    <div class="side-label">Tanggal</div>
                    <div class="side-value">{{ $reportDate }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Shift</div>
                    <div class="side-value">{{ $trafficReport->shift ?? '-' }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Kendaraan Masuk</div>
                    <div class="side-value">{{ number_format($vehicleIn) }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Kendaraan Keluar</div>
                    <div class="side-value">{{ number_format($vehicleOut) }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Total Kendaraan</div>
                    <div class="side-value">{{ number_format($totalVehicle) }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Total Transaksi</div>
                    <div class="side-value">{{ number_format($totalTransaction) }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Total Pendapatan</div>
                    <div class="side-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diperbarui</div>
                    <div class="side-value">{{ $trafficReport->updated_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                </div>

                <hr>

                @if ($backToRecap)
                    <div class="alert alert-info rounded-4 mb-3">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Dibuka dari Laporan Rekap
                        </div>
                        Tombol kembali akan mengarahkan ke halaman Laporan Rekap agar tidak terkena akses daftar Traffic Harian Petugas.
                    </div>
                @endif

                <div class="d-grid gap-2">
                    @if ($canModify && !$backToRecap)
                        <a href="{{ route('traffic-reports.edit', $trafficReport) }}" class="btn btn-warning text-white rounded-3">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Laporan
                        </a>
                    @endif

                    <a href="{{ $backUrl }}" class="btn btn-soft rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        {{ $backLabel }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection