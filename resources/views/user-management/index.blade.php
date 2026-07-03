@extends('layouts.app')

@section('title', 'Kelola Akun Pengguna | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Kelola Akun Pengguna')
@section('page_subtitle', 'Pengelolaan akun Petugas Parkir dan Teknisi Vendor')

@section('content')
@php
    $currentRole = Auth::user()->role ?? '';
    $isAdminOperational = $currentRole === 'admin';
    $isManager = $currentRole === 'manajer';

    $searchValue = $search ?? request('search');
    $roleValue = $role ?? request('role');
    $statusValue = $status ?? request('status');

    $activeFilterCount = 0;

    if (!empty($searchValue)) {
        $activeFilterCount++;
    }

    if (!empty($roleValue)) {
        $activeFilterCount++;
    }

    if (!empty($statusValue)) {
        $activeFilterCount++;
    }

    $roleLabel = function ($role) {
        return match ($role) {
            'petugas' => 'Petugas Parkir',
            'teknisi' => 'Teknisi Vendor',
            'manajer' => 'Manajer Operasional',
            'admin' => 'Admin Operasional',
            default => 'Pengguna',
        };
    };

    $roleBadgeClass = function ($role) {
        return match ($role) {
            'petugas' => 'bg-primary',
            'teknisi' => 'bg-info text-dark',
            'manajer' => 'bg-warning text-dark',
            'admin' => 'bg-dark',
            default => 'bg-secondary',
        };
    };

    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Aktif' => 'bg-success',
            'Tidak Aktif' => 'bg-secondary',
            default => 'bg-secondary',
        };
    };
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

    .initial-password-card {
        border-radius: 20px;
        border: 1px solid #ffe4a3;
        background:
            radial-gradient(circle at top right, rgba(255, 193, 7, 0.16), transparent 36%),
            linear-gradient(180deg, #fffaf0, #ffffff);
        padding: 20px;
    }

    .initial-password-icon {
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

    .password-display {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid #ffe4a3;
        background: #ffffff;
        color: #dc3545;
        font-size: 22px;
        font-weight: 950;
        letter-spacing: 0.04em;
    }

    .access-info-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .access-info-icon {
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

    .access-info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .access-info-value {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .summary-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 0;
    }

    .summary-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .summary-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .summary-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .user-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .user-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .user-table tbody tr:hover {
        background: #f8fbff;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 16px;
        background: linear-gradient(145deg, #0b3969, #07264c);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 950;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 10px 20px rgba(7, 38, 76, 0.18);
    }

    .user-avatar img {
        width: 44px;
        height: 44px;
        object-fit: cover;
    }

    .user-name {
        color: #071b4d;
        font-size: 14px;
        font-weight: 950;
        margin-bottom: 2px;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .empty-state {
        padding: 56px 16px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: 24px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin: 0 auto 16px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .summary-value {
            font-size: 24px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-people"></i>
            </div>

            <div>
                <h3 class="page-title-local">
                    @if ($isAdminOperational)
                        Kelola Akun Pengguna
                    @else
                        Data Pengguna Operasional
                    @endif
                </h3>

                <p class="page-subtitle-local">
                    @if ($isAdminOperational)
                        Admin Operasional dapat membuat, mengubah, reset password, dan menonaktifkan akun Petugas Parkir atau Teknisi Vendor.
                    @else
                        Manajer Operasional dapat melihat data pengguna operasional untuk kebutuhan monitoring.
                    @endif
                </p>
            </div>
        </div>

        @if ($isAdminOperational)
            <a href="{{ route('user-management.create') }}" class="btn btn-primary rounded-3 px-3">
                <i class="bi bi-person-plus me-1"></i>
                Tambah Akun
            </a>
        @endif
    </div>

    @if (session('initial_password'))
        <div class="initial-password-card mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="initial-password-icon">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <div class="fw-bold text-warning mb-1">Password Awal Pengguna</div>
                    <div class="text-muted small fw-semibold mb-2">
                        Password awal berhasil dibuat. Berikan password ini kepada pengguna terkait untuk login pertama kali.
                    </div>

                    <div class="password-display">
                        {{ session('initial_password') }}
                    </div>

                    <div class="text-muted small fw-semibold mt-2">
                        Password ini hanya ditampilkan sekali dan database tetap menyimpan password dalam bentuk hash.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="access-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="access-info-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <div class="access-info-label">Hak Akses Modul</div>
                <div class="access-info-value">
                    {{ $isAdminOperational ? 'Admin Operasional' : 'Manajer Operasional' }}
                </div>

                <div class="text-muted small fw-semibold">
                    @if ($isAdminOperational)
                        Admin Operasional mengelola akun Petugas Parkir dan Teknisi Vendor. Login pengguna menggunakan NIK.
                    @else
                        Manajer Operasional hanya melihat data pengguna. Perubahan akun tetap dilakukan oleh Admin Operasional.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Pengguna</div>
                        <h4 class="summary-value">{{ number_format($summary['total'] ?? 0) }}</h4>
                        <div class="summary-help">Seluruh akun operasional</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Akun Aktif</div>
                        <h4 class="summary-value text-success">{{ number_format($summary['active'] ?? 0) }}</h4>
                        <div class="summary-help">Dapat login ke sistem</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Petugas Parkir</div>
                        <h4 class="summary-value text-primary">{{ number_format($summary['petugas'] ?? 0) }}</h4>
                        <div class="summary-help">Akun role Petugas</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Teknisi Vendor</div>
                        <h4 class="summary-value text-info">{{ number_format($summary['teknisi'] ?? 0) }}</h4>
                        <div class="summary-help">Akun role Teknisi</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Pengguna</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan NIK, nama lengkap, email, nomor telepon, role, atau status akun.
                </p>
            </div>

            @if ($activeFilterCount > 0)
                <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('user-management.index') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Pencarian</label>

                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari NIK, nama, email, telepon..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Role</label>

                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="petugas" {{ $roleValue === 'petugas' ? 'selected' : '' }}>
                        Petugas Parkir
                    </option>
                    <option value="teknisi" {{ $roleValue === 'teknisi' ? 'selected' : '' }}>
                        Teknisi Vendor
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>

                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ $statusValue === 'Aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="Tidak Aktif" {{ $statusValue === 'Tidak Aktif' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>

                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Terapkan
                </button>
            </div>
        </form>

        @if ($activeFilterCount > 0)
            <div class="mt-3 small text-muted fw-semibold">
                <i class="bi bi-funnel me-1"></i>
                Filter aktif: <b>{{ $activeFilterCount }}</b>
            </div>
        @endif
    </div>

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Daftar Pengguna Operasional</h5>
                <p class="section-subtitle-local">
                    Menampilkan akun Petugas Parkir dan Teknisi Vendor sesuai hak akses pengguna.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
                Menampilkan
                <b>{{ $users->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $users->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $users->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table user-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Pengguna</th>
                        <th>NIK</th>
                        <th>Role</th>
                        <th>Lokasi</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end" style="width: 250px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $item)
                        @php
                            $initial = strtoupper(substr($item->full_name ?? $item->name ?? $item->username ?? 'U', 0, 1));

                            $locationLabel = $item->operational_location_label ?? null;

                            if (empty($locationLabel) && !empty($item->parkingLocation)) {
                                $locationLabel = $item->parkingLocation->location_name ?? '-';

                                if (!empty($item->parkingLocation->location_code)) {
                                    $locationLabel .= ' (' . $item->parkingLocation->location_code . ')';
                                }
                            }

                            $locationLabel = $locationLabel ?: 'Belum ditentukan';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($users->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">
                                        @if (!empty($item->profile_photo))
                                            <img src="{{ asset('storage/' . $item->profile_photo) }}" alt="Foto Profil">
                                        @else
                                            {{ $initial }}
                                        @endif
                                    </div>

                                    <div>
                                        <div class="user-name">
                                            {{ $item->full_name ?? $item->name ?? '-' }}
                                        </div>

                                        <div class="muted-small">
                                            {{ $item->email ?? 'Email belum diisi' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $item->username ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    Login menggunakan NIK
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $roleBadgeClass($item->role ?? '') }}">
                                    {{ $roleLabel($item->role ?? '') }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $locationLabel }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->phone ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    Kontak pengguna
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($item->status ?? '') }}">
                                    {{ $item->status ?? '-' }}
                                </span>

                                @if ($item->must_change_password ?? false)
                                    <div class="text-warning small fw-semibold mt-1">
                                        <i class="bi bi-key me-1"></i>
                                        Perlu ganti password
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $item->created_at?->format('d M Y') ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    {{ $item->created_at?->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a
                                        href="{{ route('user-management.show', $item) }}"
                                        class="btn btn-sm btn-outline-primary rounded-3"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    @if ($isAdminOperational)
                                        <a
                                            href="{{ route('user-management.edit', $item) }}"
                                            class="btn btn-sm btn-warning text-white rounded-3"
                                        >
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('user-management.toggle-status', $item) }}"
                                            onsubmit="return confirm('Yakin ingin mengubah status akun ini?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $item->status === 'Aktif' ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-3"
                                            >
                                                @if ($item->status === 'Aktif')
                                                    <i class="bi bi-person-x me-1"></i>
                                                    Nonaktif
                                                @else
                                                    <i class="bi bi-person-check me-1"></i>
                                                    Aktifkan
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">
                                        Belum ada data pengguna
                                    </h6>

                                    <p class="mb-3">
                                        Data pengguna operasional belum tersedia atau tidak sesuai filter.
                                    </p>

                                    @if ($isAdminOperational)
                                        <a href="{{ route('user-management.create') }}" class="btn btn-primary rounded-3">
                                            <i class="bi bi-person-plus me-1"></i>
                                            Tambah Akun Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection