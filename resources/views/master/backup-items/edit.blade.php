@extends('layouts.app')

@section('title', 'Edit Barang Backup | Sistem Penanganan Kendala Parkir')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-4 bg-warning text-white"
                 style="width: 56px; height: 56px;">
                <i class="bi bi-pencil-square fs-3"></i>
            </div>

            <div>
                <h3 class="fw-bold mb-1">Edit Barang Backup</h3>
                <p class="text-muted mb-0">
                    Perbarui data dan stok barang backup operasional parkir.
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('backup-items.show', $backupItem) }}" class="btn btn-outline-primary rounded-3">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>

            <a href="{{ route('backup-items.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('backup-items.update', $backupItem) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4">
                    <h5 class="fw-bold mb-1">Informasi Barang</h5>
                    <p class="text-muted small mb-4">
                        Perbarui kode, nama, stok, satuan, lokasi penyimpanan, dan deskripsi.
                    </p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="item_code"
                                value="{{ old('item_code', $backupItem->item_code) }}"
                                class="form-control rounded-3 @error('item_code') is-invalid @enderror"
                            >
                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="item_name"
                                value="{{ old('item_name', $backupItem->item_name) }}"
                                class="form-control rounded-3 @error('item_name') is-invalid @enderror"
                            >
                            @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input
                                type="text"
                                name="category"
                                value="{{ old('category', $backupItem->category) }}"
                                class="form-control rounded-3 @error('category') is-invalid @enderror"
                            >
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                name="stock"
                                value="{{ old('stock', $backupItem->stock) }}"
                                min="0"
                                class="form-control rounded-3 @error('stock') is-invalid @enderror"
                            >
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Status otomatis mengikuti stok.</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="unit"
                                value="{{ old('unit', $backupItem->unit) }}"
                                class="form-control rounded-3 @error('unit') is-invalid @enderror"
                            >
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <input
                                type="text"
                                name="storage_location"
                                value="{{ old('storage_location', $backupItem->storage_location) }}"
                                class="form-control rounded-3 @error('storage_location') is-invalid @enderror"
                            >
                            @error('storage_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea
                                name="description"
                                rows="5"
                                class="form-control rounded-3 @error('description') is-invalid @enderror"
                            >{{ old('description', $backupItem->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('backup-items.show', $backupItem) }}" class="btn btn-light border rounded-3">
                            Batal
                        </a>

                        <button class="btn btn-primary rounded-3">
                            <i class="bi bi-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-2">Status Saat Ini</h5>

                    @if (($backupItem->stock ?? 0) > 0)
                        <span class="badge bg-success rounded-pill">Tersedia</span>
                    @else
                        <span class="badge bg-secondary rounded-pill">Tidak Tersedia</span>
                    @endif

                    <hr>

                    <div class="text-muted small">
                        Status akan otomatis berubah setelah stok disimpan.
                    </div>
                </div>

                <div class="alert alert-warning rounded-4 border-0">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Perhatian
                    </div>
                    Jika stok diubah menjadi 0, barang tidak akan muncul di form Permintaan Backup Petugas.
                </div>
            </div>
        </div>
    </form>
</div>
@endsection