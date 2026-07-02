@extends('layouts.app')

@section('title', 'Edit Akun Pengguna | Sistem Penanganan Kendala Parkir')

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

    .profile-mini {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .profile-avatar {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(145deg, #0b3969, #07264c);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 950;
        font-size: 23px;
        overflow: hidden;
        box-shadow: 0 12px 24px rgba(7, 38, 76, 0.20);
    }

    .profile-avatar img {
        width: 58px;
        height: 58px;
        object-fit: cover;
    }

    .badge-soft {
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 850;
        background: #eaf3ff;
        color: #0d6efd;
    }
</style>

@php
    $selectedParkingLocationId = old('parking_location_id', $user->parking_location_id);
    $initial = strtoupper(substr($user->full_name ?? $user->name ?? $user->username ?? 'U', 0, 1));

    $roleLabel = match ($user->role) {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        default => 'Pengguna Operasional',
    };
@endphp

<div class="container-fluid">
    {{-- Header --}}
    <div class="form-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-gear"></i>
            </div>

            <div>
                <h3 class="form-page-title">Edit Akun Pengguna</h3>
                <p class="form-page-subtitle">
                    Perbarui data akun Petugas Parkir atau Teknisi Vendor.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('user-management.show', $user) }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>

            <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title">Form Edit Akun</h5>
                    <p class="section-subtitle">
                        Ubah data pengguna operasional. Password tidak berubah dari halaman ini.
                    </p>
                </div>

                <form method="POST" action="{{ route('user-management.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                NIK <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username', $user->username) }}"
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
                                value="{{ old('full_name', $user->full_name ?? $user->name) }}"
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
                                value="{{ old('email', $user->email) }}"
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
                                value="{{ old('phone', $user->phone) }}"
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
                                <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>
                                    Petugas Parkir
                                </option>
                                <option value="teknisi" {{ old('role', $user->role) === 'teknisi' ? 'selected' : '' }}>
                                    Teknisi Vendor
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Admin Operasional hanya mengelola akun Petugas/Teknisi.
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
                                        {{ (string) $selectedParkingLocationId === (string) $location->id ? 'selected' : '' }}
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
                                Pengguna di lokasi operasional yang sama akan melihat history yang sama.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="Aktif" {{ old('status', $user->status) === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="Tidak Aktif" {{ old('status', $user->status) === 'Tidak Aktif' ? 'selected' : '' }}>
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
                                    History laporan, traffic, dan backup mengikuti lokasi operasional pengguna.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box warning mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-key-fill me-1"></i>
                                    Informasi Password
                                </div>
                                Perubahan data akun tidak mengubah password. Gunakan tombol reset password pada halaman detail akun jika diperlukan.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap gap-2 mt-4">
                        <a href="{{ route('user-management.show', $user) }}" class="btn btn-soft rounded-3 px-4">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="page-card p-4">
                <h5 class="section-title mb-3">Ringkasan Akun</h5>

                <div class="profile-mini mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-avatar">
                            @if (!empty($user->profile_photo))
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                            @else
                                {{ $initial }}
                            @endif
                        </div>

                        <div>
                            <div class="fw-bold text-dark">
                                {{ $user->full_name ?? $user->name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                NIK: {{ $user->username ?? '-' }}
                            </div>
                            <div class="mt-2">
                                <span class="badge-soft">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Login Menggunakan NIK</div>
                        <div class="text-muted small">
                            Kolom NIK tetap dipakai sebagai username di database.
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
                            Lokasi dipakai untuk menyamakan history antar pengguna di cabang yang sama.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon success">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">History Lokasi</div>
                        <div class="text-muted small">
                            Jika lokasi akun diganti, tampilan history akan mengikuti lokasi baru.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon warning">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Status Akun</div>
                        <div class="text-muted small">
                            Akun Tidak Aktif tidak dapat digunakan untuk login.
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4 mt-4">
                <h5 class="section-title mb-2">Lokasi Saat Ini</h5>
                <p class="text-muted small mb-0">
                    {{ $user->operational_location_label ?? 'Belum ditentukan' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection