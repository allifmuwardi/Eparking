@extends('layouts.app')

@section('title', 'Edit Akun Pengguna | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Edit Akun Pengguna')
@section('page_subtitle', 'Perbarui data akun Petugas Parkir dan Teknisi Vendor')

@section('content')
@php
    $selectedParkingLocationId = old('parking_location_id', $user->parking_location_id);
    $initial = strtoupper(substr($user->full_name ?? $user->name ?? $user->username ?? 'U', 0, 1));

    $roleLabel = match ($user->role ?? '') {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        'manajer' => 'Manajer Operasional',
        'admin' => 'Admin Operasional',
        default => 'Pengguna Operasional',
    };

    $statusBadgeClass = match ($user->status ?? '') {
        'Aktif' => 'bg-success',
        'Tidak Aktif' => 'bg-secondary',
        default => 'bg-secondary',
    };

    $locationLabel = $user->operational_location_label ?? null;

    if (empty($locationLabel) && !empty($user->parkingLocation)) {
        $locationLabel = $user->parkingLocation->location_name ?? '-';

        if (!empty($user->parkingLocation->location_code)) {
            $locationLabel .= ' (' . $user->parkingLocation->location_code . ')';
        }
    }

    $locationLabel = $locationLabel ?: 'Belum ditentukan';
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
        background: linear-gradient(145deg, #ffc107, #ef9f00);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 14px 28px rgba(255, 193, 7, 0.26);
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

    .form-section-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .field-helper {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
        display: block;
    }

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .profile-mini-card {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
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
        font-size: 23px;
        font-weight: 950;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 12px 24px rgba(7, 38, 76, 0.20);
    }

    .profile-avatar img {
        width: 58px;
        height: 58px;
        object-fit: cover;
    }

    .profile-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .profile-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .side-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #edf3fc;
    }

    .side-row:last-child {
        border-bottom: none;
    }

    .side-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 750;
    }

    .side-value {
        color: #071b4d;
        font-size: 13px;
        font-weight: 950;
        text-align: right;
    }

    .info-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .info-box.warning {
        border-color: #ffe4a3;
        background: #fff6dc;
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

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .form-section-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-gear"></i>
            </div>

            <div>
                <h3 class="page-title-local">Edit Akun Pengguna</h3>
                <p class="page-subtitle-local">
                    Perbarui data akun pengguna operasional. Password tidak berubah dari halaman ini.
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

    <form method="POST" action="{{ route('user-management.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-section-card">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Akun</h5>
                        <p class="section-subtitle-local">
                            Perbarui identitas akun, role, lokasi operasional, dan status pengguna.
                        </p>
                    </div>

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
                                required
                            >

                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                NIK digunakan sebagai username login pengguna.
                            </span>
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
                                required
                            >

                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Nama akan tampil pada dashboard dan riwayat aktivitas.
                            </span>
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

                            <span class="field-helper">
                                Boleh dikosongkan jika pengguna belum memiliki email.
                            </span>
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

                            <span class="field-helper">
                                Digunakan untuk kebutuhan koordinasi operasional.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>

                            <select
                                name="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required
                            >
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

                            <span class="field-helper">
                                Admin Operasional hanya mengelola akun Petugas Parkir dan Teknisi Vendor.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Lokasi Operasional <span class="text-danger">*</span>
                            </label>

                            <select
                                name="parking_location_id"
                                class="form-select @error('parking_location_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Pilih Lokasi Operasional</option>

                                @foreach ($parkingLocations as $location)
                                    <option
                                        value="{{ $location->id }}"
                                        {{ (string) $selectedParkingLocationId === (string) $location->id ? 'selected' : '' }}
                                    >
                                        {{ $location->location_name }}
                                        @if (!empty($location->location_code))
                                            ({{ $location->location_code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('parking_location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Akses history Petugas mengikuti lokasi operasional ini.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
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

                            <span class="field-helper">
                                Akun aktif dapat login ke sistem.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box h-100">
                                <div class="fw-bold text-primary mb-1">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    Lokasi Saat Ini
                                </div>

                                <div class="text-muted small fw-semibold">
                                    {{ $locationLabel }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box warning">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-key-fill me-1"></i>
                                    Password Tidak Diubah
                                </div>

                                Perubahan data akun tidak mengubah password. Gunakan tombol reset password pada halaman detail akun jika diperlukan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-card mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Simpan Perubahan Akun</h6>
                            <p class="text-muted small mb-0">
                                Pastikan NIK, role, lokasi operasional, dan status akun sudah benar.
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('user-management.show', $user) }}" class="btn btn-soft rounded-3 px-4">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-card sticky-top" style="top: 120px;">
                    <div class="profile-mini-card mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="profile-avatar">
                                @if (!empty($user->profile_photo))
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                                @else
                                    {{ $initial }}
                                @endif
                            </div>

                            <div>
                                <div class="profile-label">Akun Pengguna</div>
                                <div class="profile-value">
                                    {{ $user->full_name ?? $user->name ?? '-' }}
                                </div>

                                <div class="text-muted small fw-semibold">
                                    NIK: {{ $user->username ?? '-' }}
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <span class="badge rounded-pill bg-primary">
                                        {{ $roleLabel }}
                                    </span>

                                    <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                        {{ $user->status ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title-local">Ringkasan Akun</h5>
                    <p class="section-subtitle-local mb-3">
                        Informasi akun sebelum perubahan disimpan.
                    </p>

                    <div class="side-row">
                        <div class="side-label">NIK</div>
                        <div class="side-value">{{ $user->username ?? '-' }}</div>
                    </div>

                    <div class="side-row">
                        <div class="side-label">Role</div>
                        <div class="side-value">{{ $roleLabel }}</div>
                    </div>

                    <div class="side-row">
                        <div class="side-label">Lokasi</div>
                        <div class="side-value">{{ $locationLabel }}</div>
                    </div>

                    <div class="side-row">
                        <div class="side-label">Status</div>
                        <div class="side-value">
                            <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                {{ $user->status ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="side-row">
                        <div class="side-label">Dibuat</div>
                        <div class="side-value">
                            {{ $user->created_at?->format('d M Y H:i') ?? '-' }} WIB
                        </div>
                    </div>

                    <div class="side-row">
                        <div class="side-label">Diperbarui</div>
                        <div class="side-value">
                            {{ $user->updated_at?->format('d M Y H:i') ?? '-' }} WIB
                        </div>
                    </div>

                    <hr>

                    <h5 class="section-title-local">Catatan Role</h5>
                    <p class="section-subtitle-local mb-4">
                        Pastikan role sesuai tanggung jawab pengguna.
                    </p>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <div>
                            <div class="fw-bold text-dark">Petugas Parkir</div>
                            <div class="text-muted small fw-semibold">
                                Membuat laporan kendala, traffic harian, dan permintaan backup barang.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon info">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div>
                            <div class="fw-bold text-dark">Teknisi Vendor</div>
                            <div class="text-muted small fw-semibold">
                                Menangani laporan kendala yang ditugaskan oleh Manajer Operasional.
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning rounded-4 mb-0">
                        Jika akun tidak digunakan sementara, lebih aman ubah status menjadi
                        <b>Tidak Aktif</b> daripada menghapus data.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection