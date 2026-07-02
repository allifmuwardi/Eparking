@extends('layouts.app')

@section('title', 'Edit Lokasi Parkir | Sistem Penanganan Kendala Parkir')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-warning text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-pencil-square fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Edit Lokasi Parkir</h3>
                    <p class="text-muted mb-0">
                        Perbarui data lokasi parkir yang digunakan pada modul operasional.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('parking-locations.show', $parkingLocation) }}" class="btn btn-outline-primary rounded-3">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>

            <a href="{{ route('parking-locations.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('parking-locations.update', $parkingLocation) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Informasi Lokasi</h5>
                        <p class="text-muted small mb-0">
                            Perbarui kode lokasi, nama lokasi, alamat, area, PIC, dan status lokasi.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Lokasi <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="location_code"
                                value="{{ old('location_code', $parkingLocation->location_code) }}"
                                class="form-control rounded-3 @error('location_code') is-invalid @enderror"
                            >
                            @error('location_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="location_name"
                                value="{{ old('location_name', $parkingLocation->location_name) }}"
                                class="form-control rounded-3 @error('location_name') is-invalid @enderror"
                            >
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea
                                name="address"
                                rows="4"
                                class="form-control rounded-3 @error('address') is-invalid @enderror"
                            >{{ old('address', $parkingLocation->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Area / Zona</label>
                            <input
                                type="text"
                                name="area"
                                value="{{ old('area', $parkingLocation->area) }}"
                                class="form-control rounded-3 @error('area') is-invalid @enderror"
                            >
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror">
                                <option value="Aktif" {{ old('status', $parkingLocation->status) === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="Tidak Aktif" {{ old('status', $parkingLocation->status) === 'Tidak Aktif' ? 'selected' : '' }}>
                                    Tidak Aktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Lokasi aktif akan muncul pada pilihan form operasional.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama PIC</label>
                            <input
                                type="text"
                                name="pic_name"
                                value="{{ old('pic_name', $parkingLocation->pic_name) }}"
                                class="form-control rounded-3 @error('pic_name') is-invalid @enderror"
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
                                value="{{ old('pic_phone', $parkingLocation->pic_phone) }}"
                                class="form-control rounded-3 @error('pic_phone') is-invalid @enderror"
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
                    <h5 class="fw-bold mb-3">Ringkasan Lokasi</h5>

                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <th class="text-muted small ps-0">Kode</th>
                            <td class="text-end fw-semibold">{{ $parkingLocation->location_code ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small ps-0">Status</th>
                            <td class="text-end">
                                <span class="badge rounded-pill {{ $parkingLocation->status === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $parkingLocation->status ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted small ps-0">Dibuat Pada</th>
                            <td class="text-end">{{ $parkingLocation->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small ps-0">Diperbarui</th>
                            <td class="text-end">{{ $parkingLocation->updated_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="page-card p-4">
                    <h5 class="fw-bold mb-3">Catatan Edit</h5>

                    <div class="alert alert-warning mb-0">
                        Jika status diubah menjadi <b>Tidak Aktif</b>, lokasi tidak akan muncul pada pilihan form operasional baru.
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="page-card p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1">Update Lokasi Parkir</h6>
                    <p class="text-muted small mb-0">Simpan perubahan jika seluruh data sudah sesuai.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('parking-locations.index') }}" class="btn btn-light border rounded-3">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3">
                        <i class="bi bi-save me-1"></i>
                        Update Lokasi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection