@extends('layouts.app')

@section('title', 'Detail Akun Pengguna | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $currentRole = Auth::user()->role;
    $isAdminOperational = $currentRole === 'admin';
    $isManager = $currentRole === 'manajer';

    $roleLabel = match ($user->role) {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        default => 'Pengguna',
    };

    $roleBadgeClass = match ($user->role) {
        'petugas' => 'bg-primary',
        'teknisi' => 'bg-info text-dark',
        default => 'bg-secondary',
    };

    $statusBadgeClass = match ($user->status) {
        'Aktif' => 'bg-success',
        'Tidak Aktif' => 'bg-secondary',
        default => 'bg-secondary',
    };

    $initial = strtoupper(substr($user->full_name ?? $user->name ?? $user->username ?? 'U', 0, 1));
    $operationalLocation = $user->operational_location_label ?? 'Belum ditentukan';
@endphp

<style>
    .detail-page-header {
        margin-bottom: 24px;
    }

    .detail-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .detail-page-subtitle {
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

    .profile-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 34%),
            linear-gradient(180deg, #ffffff, #f8fbff);
        border: 1px solid #d7e3f7;
        border-radius: 22px;
        padding: 24px;
    }

    .profile-avatar-lg {
        width: 98px;
        height: 98px;
        border-radius: 26px;
        background: linear-gradient(145deg, #0b3969, #07264c);
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

    .profile-name {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        margin-bottom: 8px;
    }

    .profile-meta {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
    }

    .info-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
        height: 100%;
    }

    .info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .info-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 900;
        margin-bottom: 3px;
        word-break: break-word;
    }

    .info-help {
        color: #8a9abc;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 0;
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
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 18px;
        height: 100%;
    }

    .activity-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .activity-value {
        font-size: 30px;
        font-weight: 950;
        line-height: 1;
        margin-bottom: 6px;
    }

    .action-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 18px;
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

    .password-box {
        border-radius: 18px;
        background: #fff6dc;
        border: 1px solid #ffe4a3;
        padding: 18px;
        color: #946200;
    }

    .password-code {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #ffe4a3;
        border-radius: 14px;
        padding: 10px 14px;
        color: #b4232a;
        font-size: 20px;
        font-weight: 950;
    }

    .status-table th {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 800;
        padding-left: 0;
    }

    .status-table td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 750;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="detail-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <h3 class="detail-page-title">Detail Akun Pengguna</h3>
                <p class="detail-page-subtitle">
                    Informasi akun operasional Petugas Parkir atau Teknisi Vendor.
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($isAdminOperational)
                <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning text-white rounded-3 px-3">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Akun
                </a>
            @endif

            <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Password Awal --}}
    @if (session('initial_password'))
        <div class="password-box mb-4">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="note-icon warning">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <div class="fw-bold mb-1">Password Awal / Password Baru</div>
                    <div class="mb-2">
                        Berikan password berikut kepada pengguna untuk login.
                    </div>

                    <div class="password-code">
                        <span>{{ session('initial_password') }}</span>
                    </div>

                    <div class="small mt-2">
                        Password ini hanya ditampilkan sekali. Database tetap menyimpan password dalam bentuk hash.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left --}}
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="profile-hero">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="profile-avatar-lg">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil">
                            @else
                                {{ $initial }}
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <h3 class="profile-name">{{ $user->full_name ?? $user->name ?? '-' }}</h3>

                            <div class="d-flex flex-wrap gap-2 mb-3">
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

                            <div class="profile-meta">
                                NIK Login:
                                <span class="fw-bold text-dark">{{ $user->username ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lokasi Operasional --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title">Lokasi Operasional</h5>
                    <p class="section-subtitle">
                        History laporan, traffic, dan backup ditampilkan berdasarkan lokasi operasional yang sama.
                    </p>
                </div>

                <div class="location-card">
                    <div class="d-flex align-items-start gap-3">
                        <div class="location-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <div class="info-label">Lokasi / Cabang Kerja</div>
                            <div class="fs-5 fw-bold text-primary mb-1">
                                {{ $operationalLocation }}
                            </div>
                            <div class="text-muted small">
                                Pengguna lain dengan lokasi operasional yang sama akan melihat history operasional yang sama.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Akun --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title">Informasi Akun</h5>
                    <p class="section-subtitle">
                        Data identitas dan akses pengguna operasional.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">NIK</div>
                            <div class="info-value">{{ $user->username ?? '-' }}</div>
                            <p class="info-help">Digunakan untuk login ke sistem.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">NIP</div>
                            <div class="info-value">{{ $user->nip ?? '-' }}</div>
                            <p class="info-help">Disamakan dengan NIK jika tidak ada NIP khusus.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->full_name ?? $user->name ?? '-' }}</div>
                            <p class="info-help">Nama pengguna operasional.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Role</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill {{ $roleBadgeClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                            <p class="info-help mt-2">Hak akses pengguna.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email ?? '-' }}</div>
                            <p class="info-help">Kontak email.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $user->phone ?? '-' }}</div>
                            <p class="info-help">Kontak pengguna.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aktivitas --}}
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title">Aktivitas Terkait</h5>
                    <p class="section-subtitle">
                        Ringkasan jumlah data yang berkaitan dengan akun ini.
                    </p>
                </div>

                <div class="row g-3">
                    @if ($user->role === 'petugas')
                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Laporan Kendala Dibuat</div>
                                <div class="activity-value text-primary">{{ $user->issue_reports_count ?? 0 }}</div>
                                <div class="text-muted small">Berdasarkan akun pembuat.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Traffic Harian Dibuat</div>
                                <div class="activity-value text-success">{{ $user->daily_traffic_reports_count ?? 0 }}</div>
                                <div class="text-muted small">Berdasarkan akun pembuat.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="activity-card">
                                <div class="activity-label">Permintaan Backup Dibuat</div>
                                <div class="activity-value text-warning">{{ $user->backup_requests_count ?? 0 }}</div>
                                <div class="text-muted small">Berdasarkan akun pembuat.</div>
                            </div>
                        </div>
                    @endif

                    @if ($user->role === 'teknisi')
                        <div class="col-md-12">
                            <div class="activity-card">
                                <div class="activity-label">Laporan Ditugaskan</div>
                                <div class="activity-value text-primary">{{ $user->assigned_reports_count ?? 0 }}</div>
                                <div class="text-muted small">Jumlah laporan yang ditugaskan ke teknisi ini.</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="alert alert-primary rounded-4 border-0 mt-4 mb-0">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Catatan History Lokasi
                    </div>
                    History dashboard Petugas/Teknisi akan mengikuti lokasi operasional.
                    Jadi pengguna berbeda dengan lokasi yang sama dapat melihat history operasional yang sama.
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="col-lg-4">
            <div class="page-card p-4 mb-4">
                <h5 class="section-title mb-3">Status Akun</h5>

                <table class="table table-borderless align-middle mb-0 status-table">
                    <tr>
                        <th class="ps-0">Status</th>
                        <td class="text-end">
                            <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                {{ $user->status ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th class="ps-0">Password Pertama</th>
                        <td class="text-end">
                            @if ($user->must_change_password ?? false)
                                <span class="badge bg-warning text-dark rounded-pill">Belum Diganti</span>
                            @else
                                <span class="badge bg-success rounded-pill">Normal</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="ps-0">Lokasi</th>
                        <td class="text-end">{{ $operationalLocation }}</td>
                    </tr>

                    <tr>
                        <th class="ps-0">Dibuat</th>
                        <td class="text-end">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="ps-0">Diperbarui</th>
                        <td class="text-end">{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            @if ($isAdminOperational)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Aksi Admin Operasional</h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning text-white rounded-3">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Akun
                        </a>

                        <form
                            method="POST"
                            action="{{ route('user-management.reset-password', $user) }}"
                            onsubmit="return confirm('Reset password akun ini? Password baru akan dibuat otomatis.')"
                        >
                            @csrf

                            <button class="btn btn-outline-danger rounded-3 w-100">
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

                            <button class="btn {{ $user->status === 'Aktif' ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-3 w-100">
                                @if ($user->status === 'Aktif')
                                    <i class="bi bi-person-x me-1"></i>
                                    Nonaktifkan Akun
                                @else
                                    <i class="bi bi-person-check me-1"></i>
                                    Aktifkan Akun
                                @endif
                            </button>
                        </form>
                    </div>

                    <div class="alert alert-warning rounded-4 border-0 mt-3 mb-0">
                        <div class="fw-bold mb-1">Catatan</div>
                        Akun tidak dihapus permanen agar riwayat laporan tetap aman.
                    </div>
                </div>
            @endif

            @if ($isManager)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-2">Akses Manajer</h5>

                    <div class="alert alert-primary rounded-4 border-0 mb-0">
                        Manajer Operasional hanya dapat melihat data pengguna. Perubahan akun dilakukan oleh Admin Operasional.
                    </div>
                </div>
            @endif

            <div class="page-card p-4">
                <h5 class="section-title mb-3">Informasi Sistem</h5>

                <div class="note-item">
                    <div class="note-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Login dengan NIK</div>
                        <div class="text-muted small">
                            Pengguna login menggunakan NIK: {{ $user->username ?? '-' }}.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Hak Akses Role</div>
                        <div class="text-muted small">
                            Role akun ini adalah {{ $roleLabel }}.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon info">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Lokasi Operasional</div>
                        <div class="text-muted small">
                            Lokasi menentukan history operasional yang dapat dilihat pengguna.
                        </div>
                    </div>
                </div>

                <div class="note-item">
                    <div class="note-icon warning">
                        <i class="bi bi-key"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Password Awal</div>
                        <div class="text-muted small">
                            Password awal atau hasil reset hanya ditampilkan sekali setelah dibuat.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection