@extends('layouts.app')

@section('title', 'Tambah Lokasi Parkir | Sistem Penanganan Kendala Parkir')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-plus-circle fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Tambah Lokasi Parkir</h3>
                    <p class="text-muted mb-0">
                        Tambahkan lokasi parkir baru agar dapat digunakan pada modul operasional.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('parking-locations.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('parking-locations.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Informasi Lokasi</h5>
                        <p class="text-muted small mb-0">
                            Lengkapi kode lokasi, nama lokasi, alamat, area, PIC, dan status lokasi.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Lokasi <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="location_code"
                                value="{{ old('location_code') }}"
                                class="form-control rounded-3 @error('location_code') is-invalid @enderror"
                                placeholder="Contoh: LPK001"
                            >
                            @error('location_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode unik untuk lokasi parkir.</small>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="location_name"
                                value="{{ old('location_name') }}"
                                class="form-control rounded-3 @error('location_name') is-invalid @enderror"
                                placeholder="Contoh: Area Parkir Utama"
                            >
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nama lokasi yang mudah dikenali petugas.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea
                                name="address"
                                rows="4"
                                class="form-control rounded-3 @error('address') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap lokasi parkir..."
                            >{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Area / Zona</label>
                            <input
                                type="text"
                                name="area"
                                value="{{ old('area') }}"
                                class="form-control rounded-3 @error('area') is-invalid @enderror"
                                placeholder="Contoh: Gate Utama / Basement / Outdoor"
                            >
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Area detail di dalam lokasi parkir.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror">
                                <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="Tidak Aktif" {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}>
                                    Tidak Aktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Lokasi aktif akan muncul di pilihan form operasional.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama PIC</label>
                            <input
                                type="text"
                                name="pic_name"
                                value="{{ old('pic_name') }}"
                                class="form-control rounded-3 @error('pic_name') is-invalid @enderror"
                                placeholder="Nama penanggung jawab lokasi"
                            >
                            @error('pic_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Telepon PIC</label>
                            <input
                                type="text"
                                name="pic_phone"
                                value="{{ old('pic_phone') }}"
                                class="form-control rounded-3 @error('pic_phone') is-invalid @enderror"
                                placeholder="Contoh: 081234567890"
                            >
                            @error('pic_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Panduan Lokasi</h5>

                    <div class="alert alert-primary mb-0">
                        Lokasi dengan status <b>Aktif</b> akan dapat dipilih pada laporan kendala, traffic harian, dan permintaan backup barang.
                    </div>
                </div>

                <div class="page-card p-4">
                    <h5 class="fw-bold mb-3">Contoh Pengisian</h5>

                    <div class="mb-3">
                        <div class="text-muted small">Kode Lokasi</div>
                        <div class="fw-semibold">LPK001</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Nama Lokasi</div>
                        <div class="fw-semibold">Area Parkir Utama</div>
                    </div>

                    <div>
                        <div class="text-muted small">Area / Zona</div>
                        <div class="fw-semibold">Basement / Gate Keluar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="page-card p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1">Simpan Lokasi Parkir</h6>
                    <p class="text-muted small mb-0">Pastikan data lokasi sudah benar sebelum disimpan.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('parking-locations.index') }}" class="btn btn-light border rounded-3">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3">
                        <i class="bi bi-save me-1"></i>
                        Simpan Lokasi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection