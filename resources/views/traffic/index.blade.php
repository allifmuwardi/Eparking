@extends('layouts.app')

@section('title', 'Pelaporan Traffic Harian | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();

    $totalVehicleIn = $trafficReports->sum('total_vehicle_in');
    $totalVehicleOut = $trafficReports->sum('total_vehicle_out');
    $totalTransaction = $trafficReports->sum('total_transaction');
    $totalRevenue = $trafficReports->sum('total_revenue');

    $operationalLocationLabel = 'Belum ditentukan';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->area_zone)) {
            $operationalLocationLabel .= ' - ' . $authUser->parkingLocation->area_zone;
        }
    }
@endphp

<style>
    .traffic-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .traffic-page-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 600;
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

    .summary-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        height: 100%;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        margin-bottom: 0;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .summary-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .summary-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
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

    .btn-primary {
        background: linear-gradient(135deg, #1f6de2, #0649bd);
        border: none;
        font-weight: 850;
        box-shadow: 0 12px 22px rgba(13, 110, 253, 0.20);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0d63dd, #003f9d);
    }

    .btn-soft {
        border: 1px solid #d7e3f7;
        background: #ffffff;
        color: #071b4d;
        font-weight: 800;
    }

    .btn-soft:hover {
        background: #f3f8ff;
        border-color: #b9cbea;
    }

    .form-label {
        color: #071b4d;
        font-size: 14px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .form-control {
        min-height: 48px;
        border-radius: 13px;
        border: 1px solid #d7e3f7;
        background-color: #f8fbff;
        color: #071b4d;
        font-weight: 650;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .location-info-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .location-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .location-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .location-value {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .table thead th {
        color: #5f719a;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .table tbody td {
        color: #071b4d;
        font-size: 14px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fbff;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-state {
        padding: 58px 16px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: 24px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin: 0 auto 16px;
    }

    .role-alert {
        border-radius: 18px;
        border: none;
        padding: 18px;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-car-front"></i>
            </div>

            <div>
                <h3 class="traffic-page-title">Pelaporan Traffic Harian</h3>
                <p class="traffic-page-subtitle">
                    History traffic harian pada lokasi operasional yang sama.
                </p>
            </div>
        </div>

        <a href="{{ route('traffic-reports.create') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i>
            Input Traffic Harian
        </a>
    </div>

    {{-- Info Lokasi --}}
    <div class="location-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="location-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>

            <div>
                <div class="location-label">History Lokasi Operasional</div>
                <div class="location-value">{{ $operationalLocationLabel }}</div>
                <div class="text-muted small">
                    Daftar di bawah ini menampilkan traffic harian dari semua Petugas yang berada di lokasi operasional yang sama.
                </div>
            </div>
        </div>
    </div>

    {{-- Info Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Total Data</div>
                        <h4 class="summary-value">{{ number_format($trafficReports->total()) }}</h4>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Kendaraan Masuk</div>
                        <h4 class="summary-value">{{ number_format($totalVehicleIn) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Total Transaksi</div>
                        <h4 class="summary-value">{{ number_format($totalTransaction) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Total Pendapatan</div>
                        <h5 class="fw-bold mb-0 text-success">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h5>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Alur --}}
    <div class="alert alert-primary role-alert mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1"></i>
            Alur Traffic Harian
        </div>
        Petugas dapat melihat history traffic berdasarkan <b>lokasi operasional yang sama</b>.
        Namun edit dan hapus hanya dapat dilakukan oleh akun pembuat laporan traffic.
    </div>

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title">Filter Traffic Harian</h5>
                <p class="section-subtitle">
                    Cari laporan berdasarkan tanggal, shift, lokasi, atau pembuat laporan.
                </p>
            </div>

            @if (!empty($search))
                <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('traffic-reports.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Cari tanggal, shift, lokasi, atau pembuat laporan..."
                >
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title">Daftar Traffic Harian</h5>
                <p class="section-subtitle">
                    Menampilkan history traffic harian pada lokasi operasional yang sama.
                </p>
            </div>

            <div class="text-muted small">
                Menampilkan
                <b>{{ $trafficReports->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $trafficReports->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $trafficReports->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Pembuat</th>
                        <th>Lokasi Operasional</th>
                        <th>Shift</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                        <th class="text-end">Transaksi</th>
                        <th class="text-end">Pendapatan</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($trafficReports as $report)
                        @php
                            $locationLabel = $report->parkingLocation->location_name ?? '-';

                            if (!empty($report->parkingLocation->area_zone)) {
                                $locationLabel .= ' - ' . $report->parkingLocation->area_zone;
                            }

                            $reporterName = $report->user->full_name
                                ?? $report->user->name
                                ?? '-';

                            $isOwner = $report->user_id === $authUser->id;
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ $trafficReports->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $report->report_date?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    ID: {{ $report->id }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $reporterName }}
                                </div>
                                <div class="muted-small">
                                    NIK: {{ $report->user->username ?? '-' }}
                                </div>

                                @if ($isOwner)
                                    <span class="badge rounded-pill bg-primary mt-1">
                                        Laporan Anda
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-info text-dark mt-1">
                                        History Lokasi
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $locationLabel }}
                                </div>
                                <div class="muted-small">
                                    Kode: {{ $report->parkingLocation->location_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-primary">
                                    {{ $report->shift ?? '-' }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">
                                    {{ number_format($report->total_vehicle_in ?? 0) }}
                                </div>
                                <div class="muted-small">kendaraan</div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">
                                    {{ number_format($report->total_vehicle_out ?? 0) }}
                                </div>
                                <div class="muted-small">kendaraan</div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">
                                    {{ number_format($report->total_transaction ?? 0) }}
                                </div>
                                <div class="muted-small">transaksi</div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold text-success">
                                    Rp {{ number_format($report->total_revenue ?? 0, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a href="{{ route('traffic-reports.show', $report) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    @if ($isOwner)
                                        <a href="{{ route('traffic-reports.edit', $report) }}"
                                           class="btn btn-sm btn-warning text-white rounded-3">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('traffic-reports.destroy', $report) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus laporan traffic ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if (!$isOwner)
                                    <div class="muted-small mt-1">
                                        History lokasi
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada laporan traffic harian</h6>

                                    <p class="mb-3">
                                        Belum ada laporan traffic harian pada lokasi operasional Anda.
                                    </p>

                                    <a href="{{ route('traffic-reports.create') }}" class="btn btn-primary rounded-3">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Input Traffic Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($trafficReports->hasPages())
            <div class="mt-4">
                {{ $trafficReports->links() }}
            </div>
        @endif
    </div>

    {{-- Panduan Uji Logic --}}
    <div class="page-card p-4 mt-4">
        <h5 class="section-title mb-2">Kapan Bisa Uji Logic?</h5>
        <p class="section-subtitle mb-3">
            Setelah file ini disimpan, kamu sudah bisa mulai uji logic utama lokasi operasional.
        </p>

        <div class="alert alert-success rounded-4 border-0 mb-3">
            <div class="fw-bold mb-1">
                <i class="bi bi-check-circle-fill me-1"></i>
                Sudah bisa diuji sekarang
            </div>
            Pastikan migration sudah jalan, user Petugas/Teknisi sudah memiliki Lokasi Operasional, lalu login sebagai Petugas.
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Step</th>
                        <th>Yang Diuji</th>
                        <th>Hasil yang Harus Terjadi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Login sebagai Admin Operasional</td>
                        <td>Buat/edit akun Petugas dan pilih Lokasi Operasional.</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Login sebagai Petugas A</td>
                        <td>Buat laporan kendala, traffic harian, dan backup request.</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Login sebagai Petugas B di lokasi yang sama</td>
                        <td>Data Petugas A harus tampil sebagai History Lokasi.</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Login sebagai Petugas C di lokasi berbeda</td>
                        <td>Data Petugas A tidak boleh tampil.</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Edit/hapus data dari Petugas B</td>
                        <td>Tombol edit/hapus tidak tampil karena bukan pembuat data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection