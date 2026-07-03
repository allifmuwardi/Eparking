@extends('layouts.app')

@section('title', 'Profil Saya | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola informasi profil, foto profil, dan password akun')

@section('content')
@php
    $roleLabel = match ($user->role ?? '') {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        'manajer' => 'Manajer Operasional',
        'admin' => 'Admin Operasional',
        default => 'Pengguna',
    };

    $roleBadgeClass = match ($user->role ?? '') {
        'petugas' => 'bg-primary',
        'teknisi' => 'bg-info text-dark',
        'manajer' => 'bg-warning text-dark',
        'admin' => 'bg-dark',
        default => 'bg-secondary',
    };

    $statusBadgeClass = match ($user->status ?? '') {
        'Aktif' => 'bg-success',
        'Tidak Aktif' => 'bg-secondary',
        default => 'bg-secondary',
    };

    $displayName = $user->full_name ?? $user->name ?? 'User';
    $initial = strtoupper(substr($displayName, 0, 1));

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

    .profile-hero {
        border-radius: 24px;
        padding: 24px;
        color: #ffffff;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 36%),
            linear-gradient(135deg, #0b3969 0%, #0649bd 55%, #0d6efd 100%);
        box-shadow: 0 22px 50px rgba(13, 110, 253, 0.20);
        overflow: hidden;
        position: relative;
    }

    .profile-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -90px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .profile-hero-content {
        position: relative;
        z-index: 1;
    }

    .profile-avatar-lg {
        width: 104px;
        height: 104px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 950;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 18px 34px rgba(7, 38, 76, 0.22);
    }

    .profile-avatar-lg img {
        width: 104px;
        height: 104px;
        object-fit: cover;
    }

    .profile-label {
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .profile-name {
        color: #ffffff;
        font-size: 26px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .profile-meta {
        color: rgba(255, 255, 255, 0.84);
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.6;
    }

    .profile-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .profile-photo-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .profile-photo-preview {
        width: 126px;
        height: 126px;
        border-radius: 34px;
        background: linear-gradient(145deg, #0b3969, #07264c);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 950;
        overflow: hidden;
        margin: 0 auto 16px;
        box-shadow: 0 16px 32px rgba(7, 38, 76, 0.20);
    }

    .profile-photo-preview img {
        width: 126px;
        height: 126px;
        object-fit: cover;
    }

    .field-helper {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
        display: block;
    }

    .info-box {
        height: 100%;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .info-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .info-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .warning-card {
        border-radius: 20px;
        border: 1px solid #ffe4a3;
        background:
            radial-gradient(circle at top right, rgba(255, 193, 7, 0.16), transparent 36%),
            linear-gradient(180deg, #fffaf0, #ffffff);
        padding: 20px;
    }

    .warning-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        background: linear-gradient(145deg, #ffc107, #ef9f00);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(255, 193, 7, 0.24);
    }

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
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

    .password-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .password-tip {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.10), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .profile-name {
            font-size: 22px;
        }

        .profile-avatar-lg {
            width: 84px;
            height: 84px;
            border-radius: 26px;
            font-size: 34px;
        }

        .profile-avatar-lg img {
            width: 84px;
            height: 84px;
        }

        .profile-card,
        .profile-photo-card,
        .password-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-circle"></i>
            </div>

            <div>
                <h3 class="page-title-local">Profil Saya</h3>
                <p class="page-subtitle-local">
                    Kelola informasi profil, foto profil, dan password akun Anda.
                </p>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>

    @if ($user->must_change_password ?? false)
        <div class="warning-card mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="warning-icon">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <div class="fw-bold text-warning mb-1">Password Perlu Diganti</div>
                    <div class="text-muted small fw-semibold">
                        Akun Anda masih menggunakan password awal atau password hasil reset.
                        Silakan ganti password pada form <b>Ganti Password</b> di halaman ini.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="profile-hero mb-4">
        <div class="profile-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="profile-avatar-lg">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                            @else
                                {{ $initial }}
                            @endif
                        </div>

                        <div>
                            <div class="profile-label">Akun Pengguna</div>
                            <div class="profile-name">
                                {{ $displayName }}
                            </div>

                            <p class="profile-meta">
                                Login menggunakan NIK <strong>{{ $user->username ?? '-' }}</strong>
                                sebagai <strong>{{ $roleLabel }}</strong>.
                                Lokasi operasional: <strong>{{ $locationLabel }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill {{ $roleBadgeClass }}">
                            {{ $roleLabel }}
                        </span>

                        <span class="badge rounded-pill {{ $statusBadgeClass }}">
                            {{ $user->status ?? '-' }}
                        </span>

                        @if ($user->must_change_password ?? false)
                            <span class="badge rounded-pill bg-warning text-dark">
                                Perlu Ganti Password
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="profile-photo-card mb-4 text-center">
                <div class="profile-photo-preview">
                    @if ($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                    @else
                        {{ $initial }}
                    @endif
                </div>

                <h5 class="fw-bold mb-1">
                    {{ $displayName }}
                </h5>

                <div class="text-muted small fw-semibold mb-3">
                    NIK: {{ $user->username ?? '-' }}
                </div>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $roleBadgeClass }}">
                        {{ $roleLabel }}
                    </span>

                    <span class="badge rounded-pill {{ $statusBadgeClass }}">
                        {{ $user->status ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="side-card mb-4">
                <h5 class="section-title-local">Informasi Akun</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan data akun pengguna.
                </p>

                <div class="side-row">
                    <div class="side-label">Nama</div>
                    <div class="side-value">{{ $displayName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">NIK</div>
                    <div class="side-value">{{ $user->username ?? '-' }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Email</div>
                    <div class="side-value">{{ $user->email ?? '-' }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Telepon</div>
                    <div class="side-value">{{ $user->phone ?? '-' }}</div>
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
                    <div class="side-label">Bergabung</div>
                    <div class="side-value">{{ $user->created_at?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>

            <div class="side-card">
                <h5 class="section-title-local">Catatan Keamanan</h5>
                <p class="section-subtitle-local mb-4">
                    Hal penting terkait akun dan password.
                </p>

                <div class="note-item">
                    <div class="note-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Login Menggunakan NIK</div>
                        <div class="text-muted small fw-semibold">
                            Akun ini login menggunakan NIK: {{ $user->username ?? '-' }}.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon success">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Role Akun</div>
                        <div class="text-muted small fw-semibold">
                            Hak akses Anda adalah {{ $roleLabel }}.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon warning">
                        <i class="bi bi-lock"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Keamanan Password</div>
                        <div class="text-muted small fw-semibold">
                            Gunakan password yang kuat dan jangan berikan ke orang lain.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon info">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Lokasi Operasional</div>
                        <div class="text-muted small fw-semibold">
                            Lokasi operasional menjadi dasar akses data sesuai role.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="profile-card mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Update Profil</h5>
                    <p class="section-subtitle-local">
                        Perbarui nama, email, nomor telepon, dan foto profil Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
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
                            <label class="form-label">NIK</label>

                            <input
                                type="text"
                                value="{{ $user->username ?? '-' }}"
                                class="form-control"
                                readonly
                            >

                            <span class="field-helper">
                                NIK hanya dapat diubah oleh Admin Operasional.
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
                                Email digunakan untuk identitas kontak pengguna.
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
                                Nomor telepon digunakan untuk koordinasi operasional.
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Foto Profil</label>

                            <input
                                type="file"
                                name="profile_photo"
                                class="form-control @error('profile_photo') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/jpg"
                            >

                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                            </span>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <div class="info-label">Status Profile</div>
                                <div class="info-value">
                                    {{ $user->profile_photo ? 'Foto profil sudah tersedia' : 'Foto profil belum diunggah' }}
                                </div>
                                <div class="info-help">
                                    Foto profil membantu membedakan akun pengguna pada dashboard dan halaman data user.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                        <div class="text-muted small fw-semibold">
                            Pastikan data profil sudah benar sebelum disimpan.
                        </div>

                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-save me-1"></i>
                            Simpan Profil
                        </button>
                    </div>
                </form>

                @if ($user->profile_photo)
                    <form
                        method="POST"
                        action="{{ route('profile.photo.delete') }}"
                        class="mt-3"
                        onsubmit="return confirm('Yakin ingin menghapus foto profil?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline-danger rounded-3">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Foto Profil
                        </button>
                    </form>
                @endif
            </div>

            <div class="password-card">
                <div class="mb-4">
                    <h5 class="section-title-local">Ganti Password</h5>
                    <p class="section-subtitle-local">
                        Ubah password akun Anda secara mandiri untuk menjaga keamanan akses sistem.
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan password saat ini"
                                autocomplete="current-password"
                                required
                            >

                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Masukkan password yang saat ini digunakan untuk login.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Gunakan minimal 8 karakter.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password baru"
                                autocomplete="new-password"
                                required
                            >

                            <span class="field-helper">
                                Harus sama dengan password baru.
                            </span>
                        </div>

                        <div class="col-12">
                            <div class="password-tip">
                                <div class="fw-bold text-primary mb-1">
                                    <i class="bi bi-shield-lock-fill me-1"></i>
                                    Tips Password
                                </div>

                                <div class="text-muted small fw-semibold">
                                    Gunakan kombinasi huruf besar, huruf kecil, angka, atau simbol.
                                    Jangan gunakan password yang mudah ditebak seperti tanggal lahir atau NIK.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-warning text-white rounded-3 px-4">
                            <i class="bi bi-key me-1"></i>
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection