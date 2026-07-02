@extends('layouts.app')

@section('title', 'Laporan Ditugaskan | Sistem Penanganan Kendala Parkir')

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

    $activeFilterCount = 0;

    if (!empty($search)) {
        $activeFilterCount++;
    }

    if (!empty($status)) {
        $activeFilterCount++;
    }

    $processCount = $reports->where('status', 'Dalam Proses')->count();
    $infoCount = $reports->where('status', 'Menunggu Informasi')->count();
    $doneCount = $reports->where('status', 'Selesai Ditangani')->count();
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-tools fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Laporan Ditugaskan</h3>
                    <p class="text-muted mb-0">
                        Daftar laporan kendala parkir yang ditugaskan kepada Anda sebagai Teknisi Vendor.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    <div class="alert alert-primary border-0 rounded-4 mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1"></i>
            Alur Teknisi Vendor
        </div>
        Teknisi hanya dapat melihat laporan yang ditugaskan langsung oleh Manajer Operasional. Setelah pekerjaan selesai, ubah status menjadi <b>Selesai Ditangani</b> agar Manajer dapat menutup laporan.
    </div>

    {{-- Summary --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Total Tugas</div>
                        <h4 class="fw-bold mb-0">{{ $reports->total() }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-clipboard-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Dalam Proses</div>
                        <h4 class="fw-bold mb-0">{{ $processCount }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-info bg-opacity-10 text-info"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-gear fs-4"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Berdasarkan halaman ini</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Menunggu Informasi</div>
                        <h4 class="fw-bold mb-0">{{ $infoCount }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-question-circle fs-4"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Berdasarkan halaman ini</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Selesai Ditangani</div>
                        <h4 class="fw-bold mb-0">{{ $doneCount }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-success bg-opacity-10 text-success"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Berdasarkan halaman ini</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Filter Laporan Ditugaskan</h5>
                <p class="text-muted small mb-0">
                    Cari laporan berdasarkan nomor laporan, judul, lokasi, petugas, kategori, prioritas, atau status.
                </p>
            </div>

            @if (!empty($search) || !empty($status))
                <a href="{{ route('technician-reports.index') }}" class="btn btn-light border rounded-3">
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
                    value="{{ $search }}"
                    class="form-control rounded-3"
                    placeholder="Cari nomor laporan, judul, lokasi, petugas, kategori..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select rounded-3">
                    <option value="">Semua Status</option>
                    <option value="Dalam Proses" {{ $status === 'Dalam Proses' ? 'selected' : '' }}>
                        Dalam Proses
                    </option>
                    <option value="Menunggu Informasi" {{ $status === 'Menunggu Informasi' ? 'selected' : '' }}>
                        Menunggu Informasi
                    </option>
                    <option value="Selesai Ditangani" {{ $status === 'Selesai Ditangani' ? 'selected' : '' }}>
                        Selesai Ditangani
                    </option>
                    <option value="Ditutup / Diarsipkan" {{ $status === 'Ditutup / Diarsipkan' ? 'selected' : '' }}>
                        Ditutup / Diarsipkan
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Terapkan
                </button>
            </div>
        </form>

        @if ($activeFilterCount > 0)
            <div class="mt-3 small text-muted">
                <i class="bi bi-funnel me-1"></i>
                Filter aktif: <b>{{ $activeFilterCount }}</b>
            </div>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Laporan Ditugaskan</h5>
                <p class="text-muted small mb-0">
                    Menampilkan laporan kendala yang saat ini ditugaskan kepada Anda.
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
                            $reporterName = $report->reporter->full_name
                                ?? $report->reporter->name
                                ?? '-';

                            $verifierName = $report->verifier->full_name
                                ?? $report->verifier->name
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
                                <div class="text-muted small">
                                    ID: {{ $report->id }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $report->title ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    Kategori: {{ $report->category ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $report->parkingLocation->location_name ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    Kode: {{ $report->parkingLocation->location_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $reporterName }}
                                </div>
                                <div class="text-muted small">
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
                                <div class="fw-semibold">
                                    {{ $verifierName }}
                                </div>
                                <div class="text-muted small">
                                    {{ $report->verified_at?->format('d M Y H:i') ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $report->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $report->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('technician-reports.show', $report) }}"
                                   class="btn btn-sm btn-primary rounded-3">
                                    <i class="bi bi-eye me-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <h6 class="fw-bold mb-1">Belum ada laporan ditugaskan</h6>
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