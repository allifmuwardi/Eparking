@extends('layouts.app')

@section('title', 'Detail Lokasi Parkir | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $isActive = $parkingLocation->status === 'Aktif';
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
                    <h3 class="fw-bold mb-1">Detail Lokasi Parkir</h3>
                    <p class="text-muted mb-0">
                        Informasi lengkap lokasi parkir yang digunakan dalam operasional.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('parking-locations.edit', $parkingLocation) }}" class="btn btn-warning text-white rounded-3">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>

            <a href="{{ route('parking-locations.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="text-muted small mb-1">Kode Lokasi</div>
                <h4 class="fw-bold text-primary mb-2">
                    {{ $parkingLocation->location_code ?? '-' }}
                </h4>

                <h5 class="fw-semibold mb-2">
                    {{ $parkingLocation->location_name ?? '-' }}
                </h5>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                        {{ $parkingLocation->status ?? '-' }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        {{ $parkingLocation->area ?? 'Area belum diisi' }}
                    </span>
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Dibuat Pada</div>
                <div class="fw-bold">
                    {{ $parkingLocation->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="text-muted small mt-2">Diperbarui Pada</div>
                <div class="fw-bold">
                    {{ $parkingLocation->updated_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Informasi Lokasi</h5>
                    <p class="text-muted small mb-0">
                        Detail data master lokasi parkir.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Kode Lokasi</div>
                            <div class="fw-semibold">{{ $parkingLocation->location_code ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Nama Lokasi</div>
                            <div class="fw-semibold">{{ $parkingLocation->location_name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small mb-1">Alamat</div>
                            <div style="white-space: pre-line;">
                                {{ $parkingLocation->address ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Area / Zona</div>
                            <div class="fw-semibold">{{ $parkingLocation->area ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Status</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $parkingLocation->status ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Nama PIC</div>
                            <div class="fw-semibold">{{ $parkingLocation->pic_name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">No. Telepon PIC</div>
                            <div class="fw-semibold">{{ $parkingLocation->pic_phone ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="page-card p-4 mb-4 text-center">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary mx-auto mb-3"
                     style="width: 96px; height: 96px;">
                    <i class="bi bi-geo-alt-fill" style="font-size: 52px;"></i>
                </div>

                <h5 class="fw-bold mb-1">{{ $parkingLocation->location_code ?? '-' }}</h5>
                <p class="text-muted mb-3">{{ $parkingLocation->location_name ?? '-' }}</p>

                <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    {{ $parkingLocation->status ?? '-' }}
                </span>
            </div>

            <div class="page-card p-4">
                <h5 class="fw-bold mb-3">Aksi Lokasi</h5>

                <div class="d-grid gap-2">
                    <a href="{{ route('parking-locations.edit', $parkingLocation) }}" class="btn btn-warning text-white rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Lokasi
                    </a>

                    <a href="{{ route('parking-locations.index') }}" class="btn btn-light border rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke List
                    </a>

                    <form method="POST"
                          action="{{ route('parking-locations.destroy', $parkingLocation) }}"
                          onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger rounded-3 w-100">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Lokasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection