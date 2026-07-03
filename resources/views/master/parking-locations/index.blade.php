@extends('layouts.app')

@section('title', 'Master Lokasi Parkir | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Master Lokasi Parkir')
@section('page_subtitle', 'Pengelolaan master lokasi operasional parkir')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return $status === 'Aktif' ? 'bg-success' : 'bg-secondary';
    };

    $searchValue = $search ?? request('search');
    $activeFilterCount = !empty($searchValue) ? 1 : 0;

    $activeOnPage = $locations->where('status', 'Aktif')->count();
    $inactiveOnPage = $locations->where('status', 'Tidak Aktif')->count();
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

    .master-info-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .master-info-icon {
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

    .master-info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .master-info-value {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 3px;
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
        font-size: 28px;
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

    .summary-icon.secondary {
        background: #eef2f7;
        color: #64748b;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .location-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .location-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .location-table tbody tr:hover {
        background: #f8fbff;
    }

    .location-code-link {
        color: #0d6efd;
        font-size: 14px;
        font-weight: 950;
        text-decoration: none;
    }

    .location-code-link:hover {
        color: #0649bd;
        text-decoration: underline;
    }

    .location-name {
        color: #071b4d;
        font-size: 14px;
        font-weight: 900;
        margin-bottom: 2px;
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
        .page-title-local {
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
                <i class="bi bi-geo-alt"></i>
            </div>

            <div>
                <h3 class="page-title-local">Master Lokasi Parkir</h3>
                <p class="page-subtitle-local">
                    Kelola data lokasi parkir yang digunakan untuk pelaporan kendala, traffic harian, dan permintaan backup barang.
                </p>
            </div>
        </div>

        <a href="{{ route('parking-locations.create') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Lokasi
        </a>
    </div>

    <div class="master-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="master-info-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>

            <div>
                <div class="master-info-label">Master Data Operasional</div>
                <div class="master-info-value">Lokasi Parkir</div>
                <div class="text-muted small fw-semibold">
                    Lokasi aktif akan tersedia untuk assignment akun pengguna, pelaporan kendala, traffic harian,
                    dan proses permintaan backup barang.
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Lokasi</div>
                        <h4 class="summary-value">{{ number_format($locations->total()) }}</h4>
                        <div class="summary-help">Seluruh lokasi sesuai filter</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Lokasi Aktif</div>
                        <h4 class="summary-value text-success">{{ number_format($activeOnPage) }}</h4>
                        <div class="summary-help">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Tidak Aktif</div>
                        <h4 class="summary-value text-secondary">{{ number_format($inactiveOnPage) }}</h4>
                        <div class="summary-help">Tidak muncul pada input baru</div>
                    </div>

                    <div class="summary-icon secondary">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Filter Aktif</div>
                        <h4 class="summary-value text-warning">{{ number_format($activeFilterCount) }}</h4>
                        <div class="summary-help">Parameter pencarian aktif</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-funnel"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Lokasi Parkir</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan kode lokasi, nama lokasi, area, alamat, nama PIC, atau nomor kontak.
                </p>
            </div>

            @if (!empty($searchValue))
                <a href="{{ route('parking-locations.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('parking-locations.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari kode lokasi, nama lokasi, area, PIC, atau kontak..."
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
                <h5 class="section-title-local">Daftar Lokasi Parkir</h5>
                <p class="section-subtitle-local">
                    Lokasi aktif akan muncul pada modul pelaporan kendala, traffic harian, dan permintaan backup barang.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
                Menampilkan
                <b>{{ $locations->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $locations->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $locations->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table location-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Kode</th>
                        <th>Informasi Lokasi</th>
                        <th>Area / Zona</th>
                        <th>PIC</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($locations as $location)
                        <tr>
                            <td class="text-muted">
                                {{ ($locations->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('parking-locations.show', $location) }}" class="location-code-link">
                                    {{ $location->location_code ?? '-' }}
                                </a>

                                <div class="muted-small">
                                    ID: {{ $location->id }}
                                </div>
                            </td>

                            <td>
                                <div class="location-name">
                                    {{ $location->location_name ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    {{ $location->address ? \Illuminate\Support\Str::limit($location->address, 70) : 'Alamat belum diisi' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $location->area ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $location->pic_name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $location->pic_phone ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($location->status ?? '') }}">
                                    {{ $location->status ?? '-' }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a
                                        href="{{ route('parking-locations.show', $location) }}"
                                        class="btn btn-sm btn-outline-primary rounded-3"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('parking-locations.edit', $location) }}"
                                        class="btn btn-sm btn-warning text-white rounded-3"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('parking-locations.destroy', $location) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger rounded-3">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">
                                        Data lokasi parkir belum tersedia
                                    </h6>

                                    <p class="mb-3">
                                        Tambahkan lokasi parkir agar dapat digunakan pada laporan kendala,
                                        traffic harian, dan permintaan backup barang.
                                    </p>

                                    <a href="{{ route('parking-locations.create') }}" class="btn btn-primary rounded-3">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Tambah Lokasi Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($locations->hasPages())
            <div class="mt-4">
                {{ $locations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection