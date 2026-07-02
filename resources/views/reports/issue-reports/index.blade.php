@extends('layouts.app')

@section('title', 'Pelaporan Kendala | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();

    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-primary',
            'Selesai Ditangani' => 'bg-success',
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

    $activeFilterCount = !empty($search) ? 1 : 0;

    $waitingCountOnPage = $reports->where('status', 'Menunggu Verifikasi')->count();
    $processCountOnPage = $reports->where('status', 'Dalam Proses')->count();
    $doneCountOnPage = $reports->where('status', 'Selesai Ditangani')->count();

    $operationalLocationLabel = 'Belum ditentukan';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->area_zone)) {
            $operationalLocationLabel .= ' - ' . $authUser->parkingLocation->area_zone;
        }
    }
@endphp

<style>
    .issue-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .issue-page-subtitle {
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

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .summary-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
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

    .issue-title {
        color: #071b4d;
        font-weight: 900;
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
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>
                <h3 class="issue-page-title">Pelaporan Kendala</h3>
                <p class="issue-page-subtitle">
                    History laporan kendala pada lokasi operasional yang sama.
                </p>
            </div>
        </div>

        <a href="{{ route('issue-reports.create') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i>
            Buat Laporan Kendala
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
                    Daftar di bawah ini menampilkan laporan kendala dari semua Petugas yang berada di lokasi operasional yang sama.
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
                        <h4 class="summary-value">{{ number_format($reports->total()) }}</h4>
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
                        <div class="summary-label">Menunggu Verifikasi</div>
                        <h4 class="summary-value">{{ number_format($waitingCountOnPage) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Dalam Proses</div>
                        <h4 class="summary-value">{{ number_format($processCountOnPage) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Selesai</div>
                        <h4 class="summary-value">{{ number_format($doneCountOnPage) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Alur --}}
    <div class="alert alert-primary role-alert mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1"></i>
            Alur Petugas Parkir
        </div>
        Petugas dapat melihat history laporan kendala berdasarkan <b>lokasi operasional yang sama</b>.
        Laporan yang dibuat oleh Petugas lain akan tampil sebagai history lokasi.
    </div>

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title">Filter Laporan</h5>
                <p class="section-subtitle">
                    Cari laporan berdasarkan nomor laporan, judul, kategori, status, prioritas, atau pembuat laporan.
                </p>
            </div>

            @if (!empty($search))
                <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('issue-reports.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Cari nomor laporan, judul, kategori, status, prioritas, atau pembuat laporan..."
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
                <h5 class="section-title">Daftar Laporan Kendala</h5>
                <p class="section-subtitle">
                    Menampilkan history laporan kendala pada lokasi operasional yang sama.
                </p>
            </div>

            <div class="text-muted small">
                Menampilkan
                <b>{{ $reports->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $reports->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $reports->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Laporan</th>
                        <th>Informasi Kendala</th>
                        <th>Pembuat</th>
                        <th>Lokasi Operasional</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reports as $report)
                        @php
                            $locationLabel = $report->parkingLocation->location_name ?? '-';

                            if (!empty($report->parkingLocation->area_zone)) {
                                $locationLabel .= ' - ' . $report->parkingLocation->area_zone;
                            }

                            $isOwner = $report->user_id === $authUser->id;
                            $reporterName = $report->reporter->full_name
                                ?? $report->reporter->name
                                ?? '-';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ $reports->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $report->report_number ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    ID: {{ $report->id }}
                                </div>
                            </td>

                            <td>
                                <div class="issue-title">
                                    {{ $report->title ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    {{ \Illuminate\Support\Str::limit($report->description ?? '-', 70) }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $reporterName }}
                                </div>
                                <div class="muted-small">
                                    NIK: {{ $report->reporter->username ?? '-' }}
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
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $report->category ?? '-' }}
                                </span>
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

                                @if ($report->status === 'Menunggu Verifikasi')
                                    <div class="muted-small mt-1">
                                        Menunggu Manajer
                                    </div>
                                @elseif ($report->status === 'Dalam Proses')
                                    <div class="muted-small mt-1">
                                        Sedang ditangani
                                    </div>
                                @elseif ($report->status === 'Selesai Ditangani')
                                    <div class="muted-small mt-1">
                                        Selesai teknisi
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $report->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    {{ $report->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('issue-reports.show', $report) }}"
                                   class="btn btn-sm btn-outline-primary rounded-3">
                                    <i class="bi bi-eye me-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada laporan kendala</h6>

                                    <p class="mb-3">
                                        Belum ada laporan kendala pada lokasi operasional Anda.
                                    </p>

                                    <a href="{{ route('issue-reports.create') }}" class="btn btn-primary rounded-3">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Buat Laporan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reports->hasPages())
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection