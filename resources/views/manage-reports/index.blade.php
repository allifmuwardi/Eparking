@extends('layouts.app')

@section('title', 'Manage Report | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Manage Report')
@section('page_subtitle', 'Verifikasi, penugasan teknisi, dan monitoring laporan kendala')

@section('content')
@php
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
    $statusValue = $status ?? request('status');

    $waitingCount = $reports->where('status', 'Menunggu Verifikasi')->count();
    $processCount = $reports->where('status', 'Dalam Proses')->count();
    $doneCount = $reports->whereIn('status', ['Selesai Ditangani', 'Ditutup / Diarsipkan'])->count();
@endphp

<style>
    .manage-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .manage-subtitle {
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

    .flow-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .flow-step {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: rgba(255,255,255,.88);
        padding: 16px;
        height: 100%;
    }

    .flow-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 12px;
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

    .manage-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .manage-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .manage-table tbody tr:hover {
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
        .manage-title {
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
                <i class="bi bi-kanban"></i>
            </div>

            <div>
                <h3 class="manage-title">Manage Report Kendala</h3>
                <p class="manage-subtitle">
                    Kelola laporan kendala dari Petugas, lakukan verifikasi, assign teknisi, dan close laporan.
                </p>
            </div>
        </div>

        <a href="{{ route('report-recaps.index') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-file-earmark-bar-graph me-1"></i>
            Laporan Rekap
        </a>
    </div>

    <div class="flow-card mb-4">
        <div class="mb-3">
            <h5 class="section-title-local">Alur Manajer Operasional</h5>
            <p class="section-subtitle-local">
                Manajer memvalidasi laporan, menugaskan teknisi, memantau follow up, lalu menutup laporan saat kendala sudah selesai.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon"><i class="bi bi-inbox"></i></div>
                    <div class="fw-bold">1. Laporan Masuk</div>
                    <div class="small text-muted fw-semibold mt-1">Petugas membuat laporan kendala.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon"><i class="bi bi-person-check"></i></div>
                    <div class="fw-bold">2. Verifikasi</div>
                    <div class="small text-muted fw-semibold mt-1">Manajer validasi dan assign teknisi.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon"><i class="bi bi-tools"></i></div>
                    <div class="fw-bold">3. Penanganan</div>
                    <div class="small text-muted fw-semibold mt-1">Teknisi melakukan update follow up.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon"><i class="bi bi-archive"></i></div>
                    <div class="fw-bold">4. Closing</div>
                    <div class="small text-muted fw-semibold mt-1">Manajer menutup laporan selesai.</div>
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
                        <div class="summary-help">Seluruh laporan sesuai filter</div>
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
                        <h4 class="summary-value text-warning">{{ number_format($waitingCount) }}</h4>
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
                        <h4 class="summary-value text-info">{{ number_format($processCount) }}</h4>
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
                        <div class="summary-label">Selesai / Arsip</div>
                        <h4 class="summary-value text-success">{{ number_format($doneCount) }}</h4>
                        <div class="summary-help">Selesai ditangani atau ditutup</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Laporan</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan nomor laporan, judul, lokasi, petugas, teknisi, kategori, prioritas, atau status.
                </p>
            </div>

            @if (!empty($searchValue) || !empty($statusValue))
                <a href="{{ route('manage-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('manage-reports.index') }}" class="row g-3">
            <div class="col-md-7">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari nomor laporan, judul, lokasi, petugas, teknisi, kategori..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ $statusValue === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Dalam Proses" {{ $statusValue === 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="Menunggu Informasi" {{ $statusValue === 'Menunggu Informasi' ? 'selected' : '' }}>Menunggu Informasi</option>
                    <option value="Selesai Ditangani" {{ $statusValue === 'Selesai Ditangani' ? 'selected' : '' }}>Selesai Ditangani</option>
                    <option value="Ditolak" {{ $statusValue === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Ditutup / Diarsipkan" {{ $statusValue === 'Ditutup / Diarsipkan' ? 'selected' : '' }}>Ditutup / Diarsipkan</option>
                </select>
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
                    Laporan yang masuk dari Petugas Parkir dan perlu dimonitor oleh Manajer Operasional.
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
            <table class="table manage-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Laporan</th>
                        <th>Kendala</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
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
                            $technicianName = $report->assignedTechnician->full_name ?? $report->assignedTechnician->name ?? '-';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($reports->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('manage-reports.show', $report) }}" class="table-title-link">
                                    {{ $report->report_number ?? '-' }}
                                </a>
                                <div class="muted-small">{{ $report->category ?? '-' }}</div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->title ?? '-' }}</div>
                                <div class="muted-small">
                                    {{ \Illuminate\Support\Str::limit($report->description ?? '-', 70) }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->parkingLocation->location_name ?? '-' }}</div>
                                <div class="muted-small">Kode: {{ $report->parkingLocation->location_code ?? '-' }}</div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $reporterName }}</div>
                                <div class="muted-small">NIK: {{ $report->reporter->username ?? '-' }}</div>
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
                                <div class="fw-bold">{{ $technicianName }}</div>
                                <div class="muted-small">
                                    {{ $report->assignedTechnician ? 'Teknisi Vendor' : 'Belum ditugaskan' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->created_at?->format('d M Y') ?? '-' }}</div>
                                <div class="muted-small">{{ $report->created_at?->format('H:i') ?? '-' }} WIB</div>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('manage-reports.show', $report) }}"
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
                                    <p class="mb-0">
                                        Belum ada laporan yang perlu dikelola oleh Manajer Operasional.
                                    </p>
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