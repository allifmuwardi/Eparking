@extends('layouts.app')

@section('title', 'Pelaporan Kendala | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Pelaporan Kendala')
@section('page_subtitle', 'Pelaporan dan monitoring kendala operasional parkir')

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

    $searchValue = $search ?? request('search');

    $waitingCountOnPage = $reports->where('status', 'Menunggu Verifikasi')->count();
    $processCountOnPage = $reports->where('status', 'Dalam Proses')->count();
    $doneCountOnPage = $reports->where('status', 'Selesai Ditangani')->count();

    $operationalLocationLabel = 'Belum ditentukan';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->location_code)) {
            $operationalLocationLabel .= ' (' . $authUser->parkingLocation->location_code . ')';
        }
    }
@endphp

<style>
    .issue-page-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .issue-page-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.55;
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
    .summary-icon.warning { background: #fff6dc; color: #d99a00; }
    .summary-icon.info { background: #e5f8ff; color: #0bb4d8; }
    .summary-icon.success { background: #e7f7ee; color: #198754; }

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

    .issue-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .issue-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .issue-table tbody tr:hover {
        background: #f8fbff;
    }

    .table-title-link {
        color: #0d6efd;
        font-weight: 950;
    }

    .table-title-link:hover {
        color: #0649bd;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .role-alert {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background: #f8fbff;
        padding: 18px;
        color: #071b4d;
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

    @media (max-width: 768px) {
        .issue-page-title {
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
            <div class="header-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>
                <h3 class="issue-page-title">Pelaporan Kendala</h3>
                <p class="issue-page-subtitle">
                    Kelola laporan kendala operasional parkir berdasarkan lokasi operasional Anda.
                </p>
            </div>
        </div>

        <a href="{{ route('issue-reports.create') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i>
            Buat Laporan Kendala
        </a>
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
                    Petugas dapat melihat history laporan kendala pada lokasi operasional yang sama.
                    Laporan akan diverifikasi oleh Manajer Operasional sebelum ditugaskan ke Teknisi.
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Laporan</div>
                        <h4 class="summary-value">{{ number_format($reports->total()) }}</h4>
                        <div class="summary-help">Seluruh laporan sesuai akses</div>
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
                        <div class="summary-label">Menunggu Verifikasi</div>
                        <h4 class="summary-value text-warning">{{ number_format($waitingCountOnPage) }}</h4>
                        <div class="summary-help">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Dalam Proses</div>
                        <h4 class="summary-value text-info">{{ number_format($processCountOnPage) }}</h4>
                        <div class="summary-help">Sedang ditangani teknisi</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Selesai Ditangani</div>
                        <h4 class="summary-value text-success">{{ number_format($doneCountOnPage) }}</h4>
                        <div class="summary-help">Menunggu close/arsip</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="role-alert mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1 text-primary"></i>
            Alur Pelaporan Kendala
        </div>
        <div class="small fw-semibold text-muted">
            Petugas membuat laporan kendala, Manajer Operasional melakukan verifikasi dan penugasan teknisi,
            Teknisi melakukan follow up penanganan, lalu Manajer menutup laporan setelah kendala selesai.
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Laporan Kendala</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan nomor laporan, judul, kategori, prioritas, status, lokasi, atau nama petugas.
                </p>
            </div>

            @if (!empty($searchValue))
                <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('issue-reports.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Contoh: barrier, printer, tiket, Dalam Proses, Living Plaza..."
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
                <h5 class="section-title-local">Daftar Laporan Kendala</h5>
                <p class="section-subtitle-local">
                    Menampilkan laporan kendala sesuai hak akses lokasi operasional.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
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
            <table class="table issue-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Laporan</th>
                        <th>Kendala</th>
                        <th>Lokasi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reports as $report)
                        @php
                            $reporterName = $report->reporter->full_name ?? $report->reporter->name ?? '-';
                            $isOwner = (int) ($report->user_id ?? 0) === (int) ($authUser->id ?? 0);
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($reports->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('issue-reports.show', $report) }}" class="table-title-link">
                                    {{ $report->report_number ?? '-' }}
                                </a>

                                <div class="muted-small">
                                    Oleh: {{ $reporterName }}
                                </div>

                                @if ($isOwner)
                                    <span class="badge rounded-pill bg-primary mt-1">Laporan Anda</span>
                                @else
                                    <span class="badge rounded-pill bg-info text-dark mt-1">History Lokasi</span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->title ?? '-' }}</div>
                                <div class="muted-small">{{ $report->category ?? '-' }}</div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->parkingLocation->location_name ?? '-' }}</div>
                                <div class="muted-small">Kode: {{ $report->parkingLocation->location_code ?? '-' }}</div>
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
                                    {{ $report->assignedTechnician->full_name ?? $report->assignedTechnician->name ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    {{ $report->assignedTechnician ? 'Teknisi Vendor' : 'Belum ditugaskan' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $report->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    {{ $report->created_at?->format('H:i') ?? '-' }} WIB
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada laporan kendala</h6>
                                    <p class="mb-3">
                                        Belum ada laporan kendala yang tercatat pada lokasi operasional ini.
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