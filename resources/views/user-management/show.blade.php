@extends('layouts.app')

@section('title', 'Detail Akun Pengguna | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Akun Pengguna')
@section('page_subtitle', 'Informasi lengkap akun pengguna operasional')

@section('content')
@php
    $currentRole = Auth::user()->role ?? '';
    $isAdminOperational = $currentRole === 'admin';
    $isManager = $currentRole === 'manajer';

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

    $initial = strtoupper(substr($user->full_name ?? $user->name ?? $user->username ?? 'U', 0, 1));

    $operationalLocation = $user->operational_location_label ?? null;

    if (empty($operationalLocation) && !empty($user->parkingLocation)) {
        $operationalLocation = $user->parkingLocation->location_name ?? '-';

        if (!empty($user->parkingLocation->location_code)) {
            $operationalLocation .= ' (' . $user->parkingLocation->location_code . ')';
        }
    }

    $operationalLocation = $operationalLocation ?: 'Belum ditentukan';

    $isPetugas = ($user->role ?? '') === 'petugas';
    $isTeknisi = ($user->role ?? '') === 'teknisi';
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
        width: 220px;
        height: 220px;
        right: -80px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .profile-hero-content {
        position: relative;
        z-index: 1;
    }

    .profile-avatar-lg {
        width: 98px;
        height: 98px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        font-weight: 950;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 18px 34px rgba(7, 38, 76, 0.22);
    }

    .profile-avatar-lg img {
        width: 98px;
        height: 98px;
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

    .info-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
        height: 100%;
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

    .location-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .location-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .activity-card {
        height: 100%;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 18px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.04);
    }

    .activity-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .activity-value {
        font-size: 30px;
        font-weight: 950;
        line-height: 1;
        margin-bottom: 6px;
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

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .profile-name {
            font-size: 22px;
        }

        .profile-avatar-lg {
            width: 82px;
            height: 82px;
            font-size: 32px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <h3 class="page-title-local">Detail Akun Pengguna</h3>
                <p class="page-subtitle-local">
                    {{ $user->username ?? '-' }} — {{ $user->full_name ?? $user->name ?? '-' }}
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($isAdminOperational)
                <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning text-white rounded-3 px-3">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit
                </a>
            @endif

            <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="profile-hero mb-4">
        <div class="profile-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="profile-avatar-lg">
                            @if (!empty($user->profile_photo))
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                            @else
                                {{ $initial }}
                            @endif
                        </div>

                        <div>
                            <div class="profile-label">Akun Pengguna</div>
                            <div class="profile-name">
                                {{ $user->full_name ?? $user->name ?? '-' }}
                            </div>

                            <p class="profile-meta">
                                Login menggunakan NIK <strong>{{ $user->username ?? '-' }}</strong>
                                sebagai <strong>{{ $roleLabel }}</strong>.
                                Lokasi operasional: <strong>{{ $operationalLocation }}</strong>.
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

    @if (session('initial_password'))
        <div class="alert alert-warning rounded-4 border-0 mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-key-fill me-1"></i>
                Password Baru Hasil Reset
            </div>

            <div class="mb-2">
                Berikan password ini kepada pengguna terkait. Password hanya ditampilkan sekali.
            </div>

            <div class="d-inline-flex align-items-center px-3 py-2 bg-white rounded-3 border fw-bold fs-5 text-danger">
                {{ session('initial_password') }}
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Akun</h5>
                    <p class="section-subtitle-local">
                        Detail identitas pengguna, role, status akun, dan kontak operasional.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">NIK / Username</div>
                            <div class="info-value">{{ $user->username ?? '-' }}</div>
                            <div class="info-help">Digunakan untuk login ke sistem.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->full_name ?? $user->name ?? '-' }}</div>
                            <div class="info-help">Nama pengguna operasional.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Role</div>
                            <span class="badge rounded-pill {{ $roleBadgeClass }}">
                                {{ $roleLabel }}
                            </span>
                            <div class="info-help mt-2">Role menentukan akses menu dan fitur.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Status Akun</div>
                            <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                {{ $user->status ?? '-' }}
                            </span>
                            <div class="info-help mt-2">
                                {{ ($user->status ?? '') === 'Aktif' ? 'Akun dapat login ke sistem.' : 'Akun tidak dapat digunakan untuk login.' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email ?? '-' }}</div>
                            <div class="info-help">Email pengguna jika tersedia.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $user->phone ?? '-' }}</div>
                            <div class="info-help">Kontak koordinasi pengguna.</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="location-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="location-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>

                                <div>
                                    <div class="info-label">Lokasi Operasional</div>
                                    <div class="info-value fs-5">{{ $operationalLocation }}</div>
                                    <div class="info-help">
                                        Untuk Petugas Parkir, lokasi ini menentukan akses history laporan, traffic, dan backup barang.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Aktivitas Pengguna</h5>
                    <p class="section-subtitle-local">
                        Ringkasan aktivitas pengguna pada modul operasional parkir.
                    </p>
                </div>

                @if ($isPetugas)
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Laporan Kendala Dibuat</div>
                                <div class="activity-value text-primary">{{ $user->issue_reports_count ?? 0 }}</div>
                                <div class="text-muted small fw-semibold">Total laporan dari pengguna ini.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Traffic Harian Dibuat</div>
                                <div class="activity-value text-success">{{ $user->daily_traffic_reports_count ?? 0 }}</div>
                                <div class="text-muted small fw-semibold">Total laporan traffic harian.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Permintaan Backup Dibuat</div>
                                <div class="activity-value text-warning">{{ $user->backup_requests_count ?? 0 }}</div>
                                <div class="text-muted small fw-semibold">Total permintaan backup barang.</div>
                            </div>
                        </div>
                    </div>
                @elseif ($isTeknisi)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="activity-card">
                                <div class="activity-label">Laporan Ditugaskan</div>
                                <div class="activity-value text-primary">{{ $user->assigned_reports_count ?? 0 }}</div>
                                <div class="text-muted small fw-semibold">Total laporan kendala yang ditugaskan.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="activity-card">
                                <div class="activity-label">Role Pengguna</div>
                                <div class="activity-value text-info">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div class="text-muted small fw-semibold">Teknisi Vendor menangani laporan dari Manajer.</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info rounded-4 mb-0">
                        Aktivitas operasional khusus hanya ditampilkan untuk Petugas Parkir dan Teknisi Vendor.
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="side-card mb-4">
                <h5 class="section-title-local">Informasi Sistem</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan data akun pada sistem.
                </p>

                <div class="side-row">
                    <div class="side-label">ID Pengguna</div>
                    <div class="side-value">{{ $user->id }}</div>
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

                <div class="side-row">
                    <div class="side-label">Wajib Ganti Password</div>
                    <div class="side-value">
                        {{ ($user->must_change_password ?? false) ? 'Ya' : 'Tidak' }}
                    </div>
                </div>
            </div>

            <div class="side-card mb-4">
                <h5 class="section-title-local">Catatan Akses</h5>
                <p class="section-subtitle-local mb-4">
                    Hak akses pengguna mengikuti role dan lokasi operasional.
                </p>

                <div class="note-item">
                    <div class="note-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">{{ $roleLabel }}</div>
                        <div class="text-muted small fw-semibold">
                            Role menentukan menu yang dapat diakses pengguna.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon success">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Lokasi Operasional</div>
                        <div class="text-muted small fw-semibold">
                            Lokasi menjadi dasar akses data operasional pengguna.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon warning">
                        <i class="bi bi-key"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Password</div>
                        <div class="text-muted small fw-semibold">
                            Password awal atau hasil reset hanya ditampilkan sekali.
                        </div>
                    </div>
                </div>
            </div>

            @if ($isAdminOperational)
                <div class="side-card">
                    <h5 class="section-title-local">Aksi Admin</h5>
                    <p class="section-subtitle-local mb-3">
                        Kelola akun pengguna operasional.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning text-white rounded-3">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Akun
                        </a>

                        <form
                            method="POST"
                            action="{{ route('user-management.reset-password', $user) }}"
                            onsubmit="return confirm('Yakin ingin reset password akun ini?')"
                        >
                            @csrf

                            <button type="submit" class="btn btn-outline-danger rounded-3 w-100">
                                <i class="bi bi-key me-1"></i>
                                Reset Password
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('user-management.toggle-status', $user) }}"
                            onsubmit="return confirm('Yakin ingin mengubah status akun ini?')"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn {{ ($user->status ?? '') === 'Aktif' ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-3 w-100"
                            >
                                @if (($user->status ?? '') === 'Aktif')
                                    <i class="bi bi-person-x me-1"></i>
                                    Nonaktifkan Akun
                                @else
                                    <i class="bi bi-person-check me-1"></i>
                                    Aktifkan Akun
                                @endif
                            </button>
                        </form>

                        <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Daftar
                        </a>
                    </div>

                    <div class="alert alert-warning rounded-4 mt-3 mb-0">
                        Untuk akun yang tidak digunakan sementara, lebih aman ubah status menjadi
                        <b>Tidak Aktif</b> daripada menghapus data.
                    </div>
                </div>
            @else
                <div class="side-card">
                    <h5 class="section-title-local">Akses Manajer</h5>
                    <p class="section-subtitle-local mb-0">
                        Manajer Operasional hanya dapat melihat data pengguna. Perubahan akun dilakukan oleh Admin Operasional.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection