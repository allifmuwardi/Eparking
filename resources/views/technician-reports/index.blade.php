@extends('layouts.app')

@section('title', 'Laporan Ditugaskan | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Laporan Ditugaskan')
@section('page_subtitle', 'Daftar laporan kendala yang ditugaskan kepada Teknisi Vendor')

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

    $processCount = $reports->where('status', 'Dalam Proses')->count();
    $infoCount = $reports->where('status', 'Menunggu Informasi')->count();
    $doneCount = $reports->where('status', 'Selesai Ditangani')->count();

    $activeFilterCount = 0;

    if (!empty($searchValue)) {
        $activeFilterCount++;
    }

    if (!empty($statusValue)) {
        $activeFilterCount++;
    }
@endphp

<style>
    .tech-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .tech-subtitle {
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
    .summary-icon.info { background: #e5f8ff; color: #0bb4d8; }
    .summary-icon.warning { background: #fff6dc; color: #d99a00; }
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
        background: rgba(255, 255, 255, 0.90);
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

    .tech-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .tech-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .tech-table tbody tr:hover {
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
        .tech-title {
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
                <i class="bi bi-tools"></i>
            </div>

            <div>
                <h3 class="tech-title">Laporan Ditugaskan</h3>
                <p class="tech-subtitle">
                    Daftar laporan kendala parkir yang ditugaskan kepada Anda sebagai Teknisi Vendor.
                </p>
            </div>
        </div>
    </div>

    <div class="flow-card mb-4">
        <div class="mb-3">
            <h5 class="section-title-local">Alur Teknisi Vendor</h5>
            <p class="section-subtitle-local">
                Teknisi hanya dapat melihat laporan yang ditugaskan oleh Manajer Operasional.
                Setelah kendala selesai, ubah status menjadi <b>Selesai Ditangani</b> agar Manajer dapat menutup laporan.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="fw-bold">1. Menerima Tugas</div>
                    <div class="small text-muted fw-semibold mt-1">Laporan muncul setelah ditugaskan Manajer.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="fw-bold">2. Cek Kendala</div>
                    <div class="small text-muted fw-semibold mt-1">Teknisi mengecek kondisi kendala di lapangan.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="fw-bold">3. Update Follow Up</div>
                    <div class="small text-muted fw-semibold mt-1">Input catatan, dokumentasi, dan kebutuhan backup barang.</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="fw-bold">4. Selesai Ditangani</div>
                    <div class="small text-muted fw-semibold mt-1">Status dikirim kembali ke Manajer untuk closing.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Tugas</div>
                        <h4 class="summary-value">{{ number_format($reports->total()) }}</h4>
                        <div class="summary-help">Seluruh laporan ditugaskan</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-clipboard-check"></i>
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
                        <div class="summary-help">Sedang ditangani</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-gear"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Menunggu Informasi</div>
                        <h4 class="summary-value text-primary">{{ number_format($infoCount) }}</h4>
                        <div class="summary-help">Perlu data tambahan</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-question-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Selesai Ditangani</div>
                        <h4 class="summary-value text-success">{{ number_format($doneCount) }}</h4>
                        <div class="summary-help">Siap ditutup Manajer</div>
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
                <h5 class="section-title-local">Filter Laporan Ditugaskan</h5>
                <p class="section-subtitle-local">
                    Cari laporan berdasarkan nomor laporan, judul, lokasi, petugas, kategori, prioritas, atau status.
                </p>
            </div>

            @if ($activeFilterCount > 0)
                <a href="{{ route('technician-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('technician-reports.index') }}" class="row g-3">
            <div class="col-md-7">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari nomor laporan, judul, lokasi, petugas, kategori..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Dalam Proses" {{ $statusValue === 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="Menunggu Informasi" {{ $statusValue === 'Menunggu Informasi' ? 'selected' : '' }}>Menunggu Informasi</option>
                    <option value="Selesai Ditangani" {{ $statusValue === 'Selesai Ditangani' ? 'selected' : '' }}>Selesai Ditangani</option>
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

        @if ($activeFilterCount > 0)
            <div class="mt-3 small text-muted fw-semibold">
                <i class="bi bi-funnel me-1"></i>
                Filter aktif: <b>{{ $activeFilterCount }}</b>
            </div>
        @endif
    </div>

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Daftar Laporan Ditugaskan</h5>
                <p class="section-subtitle-local">
                    Menampilkan laporan kendala yang saat ini menjadi tanggung jawab Teknisi Vendor.
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
            <table class="table tech-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Laporan</th>
                        <th>Informasi Kendala</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Diverifikasi Oleh</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reports as $report)
                        @php
                            $reporterName = $report->reporter->full_name ?? $report->reporter->name ?? '-';
                            $verifierName = $report->verifier->full_name ?? $report->verifier->name ?? '-';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($reports->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('technician-reports.show', $report) }}" class="table-title-link">
                                    {{ $report->report_number ?? '-' }}
                                </a>
                                <div class="muted-small">ID: {{ $report->id }}</div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->title ?? '-' }}</div>
                                <div class="muted-small">
                                    Kategori: {{ $report->category ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $report->parkingLocation->location_name ?? '-' }}</div>
                                <div class="muted-small">
                                    Kode: {{ $report->parkingLocation->location_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $reporterName }}</div>
                                <div class="muted-small">
                                    NIK: {{ $report->reporter->username ?? '-' }}
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
                                <div class="fw-bold">{{ $verifierName }}</div>
                                <div class="muted-small">
                                    {{ $report->verified_at?->format('d M Y H:i') ?? '-' }} WIB
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
                                <a href="{{ route('technician-reports.show', $report) }}"
                                   class="btn btn-sm btn-primary rounded-3">
                                    <i class="bi bi-pencil-square me-1"></i>
                                    Update
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

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada laporan ditugaskan</h6>
                                    <p class="mb-0">
                                        Laporan akan muncul setelah Manajer Operasional menugaskan laporan kepada Anda.
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