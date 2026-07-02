@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna | Sistem Penanganan Kendala Parkir')

@section('content')
<style>
    .form-page-header {
        margin-bottom: 24px;
    }

    .form-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .form-page-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 600;
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

    .section-title {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .form-label {
        color: #071b4d;
        font-size: 14px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        min-height: 48px;
        border-radius: 13px;
        border: 1px solid #d7e3f7;
        background-color: #f8fbff;
        color: #071b4d;
        font-weight: 650;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .help-text {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }

    .info-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .info-box.warning {
        background: #fff6dc;
        border-color: #ffe4a3;
        color: #946200;
    }

    .note-item {
        display: flex;
        gap: 13px;
        margin-bottom: 18px;
    }

    .note-item:last-child {
        margin-bottom: 0;
    }

    .note-icon {
        width: 38px;
        height: 38px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 19px;
    }

    .note-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .note-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .note-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .note-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1f6de2, #0649bd);
        border: none;
        font-weight: 850;
        box-shadow: 0 12px 22px rgba(13, 110, 253, 0.20);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0d63dd, #003f9d);
    }

    .btn-soft {
        border: 1px solid #d7e3f7;
        background: #ffffff;
        color: #071b4d;
        font-weight: 800;
    }

    .btn-soft:hover {
        background: #f3f8ff;
        border-color: #b9cbea;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="form-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-plus"></i>
            </div>

            <div>
                <h3 class="form-page-title">Tambah Akun Pengguna</h3>
                <p class="form-page-subtitle">
                    Buat akun baru untuk Petugas Parkir atau Teknisi Vendor.
                </p>
            </div>
        </div>

        <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title">Form Akun Pengguna</h5>
                    <p class="section-subtitle">
                        Isi data pengguna operasional. Password awal akan dibuat otomatis oleh sistem.
                    </p>
                </div>

                <form method="POST" action="{{ route('user-management.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                NIK <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="Contoh: 1005"
                                autofocus
                            >

                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                NIK digunakan untuk login ke sistem.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                class="form-control @error('full_name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap"
                            >

                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com"
                            >

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Boleh dikosongkan jika belum ada.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Contoh: 08123456789"
                            >

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>

                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">Pilih Role</option>
                                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>
                                    Petugas Parkir
                                </option>
                                <option value="teknisi" {{ old('role') === 'teknisi' ? 'selected' : '' }}>
                                    Teknisi Vendor
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Admin Operasional hanya membuat akun Petugas/Teknisi.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Lokasi Operasional <span class="text-danger">*</span>
                            </label>

                            <select
                                name="parking_location_id"
                                class="form-select @error('parking_location_id') is-invalid @enderror"
                            >
                                <option value="">Pilih Lokasi Operasional</option>

                                @foreach ($parkingLocations as $location)
                                    <option
                                        value="{{ $location->id }}"
                                        {{ (string) old('parking_location_id') === (string) $location->id ? 'selected' : '' }}
                                    >
                                        {{ $location->location_name }}
                                        @if (!empty($location->area_zone))
                                            - {{ $location->area_zone }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('parking_location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Petugas/Teknisi dengan lokasi yang sama akan melihat history operasional yang sama.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status" class="form-select @error('status') is-invalid @enderror">
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
                        </div>

                        <div class="col-md-6">
                            <div class="info-box h-100">
                                <div class="fw-bold text-primary mb-1">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    Basis History
                                </div>
                                <div class="text-muted small">
                                    History laporan, traffic, dan backup akan mengikuti lokasi operasional user.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box warning mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-key-fill me-1"></i>
                                    Password Awal Otomatis
                                </div>
                                Setelah akun dibuat, sistem akan menampilkan password awal.
                                Password tersebut harus diberikan kepada pengguna untuk login pertama kali.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap gap-2 mt-4">
                        <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-4">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-save me-1"></i>
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="page-card p-4">
                <h5 class="section-title mb-3">Catatan</h5>

                <div class="note-item">
                    <div class="note-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Login Menggunakan NIK</div>
                        <div class="text-muted small">
                            Kolom NIK tetap disimpan sebagai username untuk proses login.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon info">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Lokasi Operasional</div>
                        <div class="text-muted small">
                            Lokasi dipakai untuk menyamakan history antar Petugas/Teknisi di cabang yang sama.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Akun Operasional</div>
                        <div class="text-muted small">
                            Akun yang dibuat hanya untuk Petugas Parkir dan Teknisi Vendor.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon warning">
                        <i class="bi bi-key"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Password Awal</div>
                        <div class="text-muted small">
                            Password dibuat otomatis dan ditampilkan setelah akun berhasil dibuat.
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4 mt-4">
                <h5 class="section-title mb-2">Contoh Logic History</h5>
                <p class="text-muted small mb-0">
                    Jika dua Petugas berada di lokasi operasional yang sama, maka keduanya dapat melihat history laporan
                    di lokasi tersebut walaupun akun pembuat laporannya berbeda.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection