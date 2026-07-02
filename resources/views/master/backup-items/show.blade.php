@extends('layouts.app')

@section('title', 'Detail Barang Backup | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $realStatus = ($backupItem->stock ?? 0) > 0 ? 'Tersedia' : 'Tidak Tersedia';
    $isAvailable = $realStatus === 'Tersedia';
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                 style="width: 56px; height: 56px;">
                <i class="bi bi-box-seam fs-3"></i>
            </div>

            <div>
                <h3 class="fw-bold mb-1">Detail Barang Backup</h3>
                <p class="text-muted mb-0">
                    Informasi lengkap barang backup operasional parkir.
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('backup-items.edit', $backupItem) }}" class="btn btn-warning text-white rounded-3">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>

            <a href="{{ route('backup-items.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="text-muted small mb-1">Kode Barang</div>
                <h4 class="fw-bold text-primary mb-2">
                    {{ $backupItem->item_code ?? '-' }}
                </h4>

                <h5 class="fw-semibold mb-2">
                    {{ $backupItem->item_name ?? '-' }}
                </h5>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $isAvailable ? 'bg-success' : 'bg-secondary' }}">
                        {{ $realStatus }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        {{ $backupItem->category ?? 'Tanpa Kategori' }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        Stok: {{ number_format($backupItem->stock ?? 0) }} {{ $backupItem->unit ?? '' }}
                    </span>
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Dibuat Pada</div>
                <div class="fw-bold">
                    {{ $backupItem->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="text-muted small mt-2">Diperbarui Pada</div>
                <div class="fw-bold">
                    {{ $backupItem->updated_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4">
                <h5 class="fw-bold mb-1">Informasi Barang</h5>
                <p class="text-muted small mb-4">
                    Detail data master barang backup.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Kode Barang</div>
                            <div class="fw-semibold">{{ $backupItem->item_code ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Nama Barang</div>
                            <div class="fw-semibold">{{ $backupItem->item_name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Kategori</div>
                            <div class="fw-semibold">{{ $backupItem->category ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Lokasi Penyimpanan</div>
                            <div class="fw-semibold">{{ $backupItem->storage_location ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Stok</div>
                            <div class="fw-bold fs-4 {{ $isAvailable ? 'text-success' : 'text-danger' }}">
                                {{ number_format($backupItem->stock ?? 0) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Satuan</div>
                            <div class="fw-semibold">{{ $backupItem->unit ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Status</div>
                            <span class="badge rounded-pill {{ $isAvailable ? 'bg-success' : 'bg-secondary' }}">
                                {{ $realStatus }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small mb-1">Deskripsi</div>
                            <div style="white-space: pre-line;">
                                {{ $backupItem->description ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Ketersediaan</h5>

                @if ($isAvailable)
                    <div class="alert alert-success rounded-4 border-0 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Barang Tersedia
                        </div>
                        Barang ini dapat dipilih oleh Petugas saat membuat Permintaan Backup.
                    </div>
                @else
                    <div class="alert alert-secondary rounded-4 border-0 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            Barang Tidak Tersedia
                        </div>
                        Barang ini tidak akan muncul di form Permintaan Backup karena stok kosong.
                    </div>
                @endif
            </div>

            <div class="page-card p-4">
                <h5 class="fw-bold mb-3">Aksi</h5>

                <div class="d-grid gap-2">
                    <a href="{{ route('backup-items.edit', $backupItem) }}" class="btn btn-warning text-white rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Barang
                    </a>

                    <form method="POST"
                          action="{{ route('backup-items.destroy', $backupItem) }}"
                          onsubmit="return confirm('Yakin ingin menghapus barang backup ini?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger rounded-3 w-100">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Barang
                        </button>
                    </form>

                    <a href="{{ route('backup-items.index') }}" class="btn btn-light border rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection