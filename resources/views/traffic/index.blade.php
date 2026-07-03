@extends('layouts.app')

@section('title', 'Traffic Harian | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Traffic Harian')
@section('page_subtitle', 'Pelaporan dan monitoring traffic operasional parkir')

@section('content')
@php
    $authUser = Auth::user();
    $role = $authUser->role ?? '';

    $totalVehicleIn = $trafficReports->sum('total_vehicle_in');
    $totalVehicleOut = $trafficReports->sum('total_vehicle_out');
    $totalTransaction = $trafficReports->sum('total_transaction');
    $totalRevenue = $trafficReports->sum('total_revenue');

    $operationalLocationLabel = 'Seluruh Lokasi / Sesuai Akses';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->location_code)) {
            $operationalLocationLabel .= ' (' . $authUser->parkingLocation->location_code . ')';
        }
    }

    $searchValue = $search ?? request('search');
@endphp

<style>
    .traffic-header-icon {
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

    .traffic-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .traffic-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.55;
    }

    .summary-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        height: 100%;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
        transition: all 0.18s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        border-color: #b9cbea;
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        margin-bottom: 0;
        line-height: 1.1;
    }

    .summary-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
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

    .summary-icon.primary { background: #eaf3ff; color: #0d6efd; }
    .summary-icon.success { background: #e7f7ee; color: #198754; }
    .summary-icon.warning { background: #fff6dc; color: #d99a00; }
    .summary-icon.info { background: #e5f8ff; color: #0bb4d8; }

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
        font-weight: 900;
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
        margin-bottom: 0;
        line-height: 1.5;
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

    .traffic-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .traffic-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .traffic-table tbody tr:hover {
        background: #f8fbff;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .table-title-link {
        color: #0d6efd;
        font-weight: 950;
    }

    .table-title-link:hover {
        color: #0649bd;
    }

    .empty-state {
        padding: 56px 16px;
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
        border: 1px solid #b9cbea;
        background: #f8fbff;
        padding: 18px;
        color: #071b4d;
    }

    @media (max-width: 768px) {
        .traffic-title {
            font-size: 22px;
        }

        .summary-value {
            font-size: 24px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="traffic-header-icon">
                <i class="bi bi-bar-chart-line"></i>
            </div>

            <div>
                <h3 class="traffic-title">Traffic Harian</h3>
                <p class="traffic-subtitle">
                    Kelola data kendaraan masuk, kendaraan keluar, transaksi, dan pendapatan operasional parkir.
                </p>
            </div>
        </div>

        @if ($role === 'petugas')
            <a href="{{ route('traffic-reports.create') }}" class="btn btn-primary rounded-3 px-3">
                <i class="bi bi-plus-circle me-1"></i>
                Input Traffic Harian
            </a>
        @endif
    </div>

    <div class="location-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="location-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>

            <div>
                <div class="location-label">Lokasi Operasional</div>
                <div class="location-value">{{ $operationalLocationLabel }}</div>
                <div class="text-muted small fw-semibold">
                    Petugas dapat melihat history traffic pada lokasi operasional yang sama.
                    Edit dan hapus hanya dapat dilakukan oleh pembuat data.
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Data</div>
                        <h4 class="summary-value">{{ number_format($trafficReports->total()) }}</h4>
                        <div class="summary-help">Seluruh laporan traffic</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Kendaraan Masuk</div>
                        <h4 class="summary-value">{{ number_format($totalVehicleIn) }}</h4>
                        <div class="summary-help">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Transaksi</div>
                        <h4 class="summary-value">{{ number_format($totalTransaction) }}</h4>
                        <div class="summary-help">Jumlah transaksi tercatat</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Pendapatan</div>
                        <h5 class="fw-bold mb-0 text-success">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h5>
                        <div class="summary-help">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="role-alert mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1 text-primary"></i>
            Alur Traffic Harian
        </div>
        <div class="small fw-semibold text-muted">
            Data traffic digunakan untuk membantu Manajer Operasional dalam membaca kondisi kendaraan,
            transaksi, dan pendapatan parkir harian. Pastikan data yang diinput sesuai kondisi operasional.
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Traffic Harian</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan tanggal, shift, lokasi, kode lokasi, atau nama pembuat laporan.
                </p>
            </div>

            @if (!empty($searchValue))
                <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('traffic-reports.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Contoh: 03 Jul 2026, Shift Pagi, Living Plaza, Petugas..."
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

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Daftar Traffic Harian</h5>
                <p class="section-subtitle-local">
                    Menampilkan data traffic harian sesuai hak akses pengguna.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
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
            <table class="table traffic-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Pembuat</th>
                        <th>Lokasi</th>
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
                            $locationCode = $report->parkingLocation->location_code ?? '-';
                            $reporterName = $report->user->full_name ?? $report->user->name ?? '-';
                            $isOwner = (int) $report->user_id === (int) $authUser->id;
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($trafficReports->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $report->report_date?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    Dibuat: {{ $report->created_at?->format('d M Y H:i') ?? '-' }} WIB
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $reporterName }}</div>
                                <div class="muted-small">NIK: {{ $report->user->username ?? '-' }}</div>

                                @if ($isOwner)
                                    <span class="badge rounded-pill bg-primary mt-1">Laporan Anda</span>
                                @else
                                    <span class="badge rounded-pill bg-info text-dark mt-1">History Lokasi</span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">{{ $locationLabel }}</div>
                                <div class="muted-small">Kode: {{ $locationCode }}</div>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-primary">
                                    {{ $report->shift ?? '-' }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">{{ number_format($report->total_vehicle_in ?? 0) }}</div>
                                <div class="muted-small">kendaraan</div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">{{ number_format($report->total_vehicle_out ?? 0) }}</div>
                                <div class="muted-small">kendaraan</div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">{{ number_format($report->total_transaction ?? 0) }}</div>
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

                                            <button type="submit" class="btn btn-sm btn-danger rounded-3">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
                                        Belum ada data traffic harian yang tercatat pada akses lokasi ini.
                                    </p>

                                    @if ($role === 'petugas')
                                        <a href="{{ route('traffic-reports.create') }}" class="btn btn-primary rounded-3">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Input Traffic Pertama
                                        </a>
                                    @endif
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
</div>
@endsection