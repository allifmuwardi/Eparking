@extends('layouts.app')

@section('title', 'Master Lokasi Parkir | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return $status === 'Aktif' ? 'bg-success' : 'bg-secondary';
    };

    $activeFilterCount = !empty($search) ? 1 : 0;
    $activeOnPage = $locations->where('status', 'Aktif')->count();
    $inactiveOnPage = $locations->where('status', 'Tidak Aktif')->count();
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-geo-alt fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Master Lokasi Parkir</h3>
                    <p class="text-muted mb-0">
                        Kelola data lokasi parkir yang digunakan untuk laporan kendala, traffic harian, dan permintaan backup.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('parking-locations.create') }}" class="btn btn-primary rounded-3">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Lokasi Parkir
        </a>
    </div>

    {{-- Info Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Total Data</div>
                <h4 class="fw-bold mb-0">{{ $locations->total() }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Lokasi Aktif</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($activeOnPage) }}</h4>
                <div class="text-muted small mt-1">Berdasarkan halaman ini.</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Tidak Aktif</div>
                <h4 class="fw-bold text-secondary mb-0">{{ number_format($inactiveOnPage) }}</h4>
                <div class="text-muted small mt-1">Berdasarkan halaman ini.</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Filter Aktif</div>
                <h4 class="fw-bold text-warning mb-0">{{ $activeFilterCount }}</h4>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Filter Lokasi Parkir</h5>
                <p class="text-muted small mb-0">
                    Cari berdasarkan kode lokasi, nama lokasi, area/zona, nama PIC, atau nomor kontak.
                </p>
            </div>

            @if (!empty($search))
                <a href="{{ route('parking-locations.index') }}" class="btn btn-light border rounded-3">
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
                    value="{{ $search }}"
                    class="form-control rounded-3"
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

    {{-- Tabel --}}
    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Lokasi Parkir</h5>
                <p class="text-muted small mb-0">
                    Lokasi aktif akan muncul pada pilihan laporan kendala, traffic harian, dan permintaan backup.
                </p>
            </div>

            <div class="text-muted small">
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
            <table class="table align-middle">
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
                                {{ $locations->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $location->location_code ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    ID: {{ $location->id }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $location->location_name ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $location->address ? Str::limit($location->address, 60) : 'Alamat belum diisi' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $location->area ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
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
                                    <a href="{{ route('parking-locations.show', $location) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    <a href="{{ route('parking-locations.edit', $location) }}"
                                       class="btn btn-sm btn-warning text-white rounded-3">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('parking-locations.destroy', $location) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger rounded-3">
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
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <h6 class="fw-bold mb-1">Data lokasi parkir belum tersedia</h6>
                                    <p class="mb-3">
                                        Tambahkan lokasi parkir agar dapat digunakan pada laporan kendala, traffic harian, dan permintaan backup.
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