@extends('layouts.app')

@section('title', 'Kelola Akun Pengguna | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $currentRole = Auth::user()->role;
    $isAdminOperational = $currentRole === 'admin';
    $isManager = $currentRole === 'manajer';

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

    $activeFilterCount = 0;

    if (!empty($search)) {
        $activeFilterCount++;
    }

    if (!empty($role)) {
        $activeFilterCount++;
    }

    if (!empty($status)) {
        $activeFilterCount++;
    }
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-people fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">
                        @if ($isAdminOperational)
                            Kelola Akun Pengguna
                        @else
                            Data Pengguna Operasional
                        @endif
                    </h3>

                    <p class="text-muted mb-0">
                        @if ($isAdminOperational)
                            Admin Operasional dapat membuat, mengubah, reset password, dan menonaktifkan akun Petugas Parkir atau Teknisi Vendor.
                        @else
                            Manajer Operasional dapat melihat data pengguna operasional sebagai kebutuhan monitoring.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($isAdminOperational)
                <a href="{{ route('user-management.create') }}" class="btn btn-primary rounded-3">
                    <i class="bi bi-person-plus me-1"></i>
                    Tambah Akun
                </a>
            @endif
        </div>
    </div>

    {{-- Info Password Awal --}}
    @if (session('initial_password'))
        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-white"
                     style="width: 42px; height: 42px;">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <div class="fw-bold mb-1">Password Awal Pengguna</div>
                    <div class="mb-2">
                        Password awal berhasil dibuat. Berikan password ini kepada pengguna terkait.
                    </div>

                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white border">
                        <span class="text-muted small">Password:</span>
                        <span class="fw-bold fs-5 text-danger">{{ session('initial_password') }}</span>
                    </div>

                    <div class="text-muted small mt-2">
                        Password ini tidak disimpan dalam bentuk teks biasa di database. Simpan atau catat sebelum meninggalkan halaman.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Total Pengguna</div>
                        <h4 class="fw-bold mb-0">{{ number_format($summary['total'] ?? 0) }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Akun Aktif</div>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($summary['active'] ?? 0) }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-success bg-opacity-10 text-success"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-person-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Petugas Parkir</div>
                        <h4 class="fw-bold mb-0 text-primary">{{ number_format($summary['petugas'] ?? 0) }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Teknisi Vendor</div>
                        <h4 class="fw-bold mb-0 text-info">{{ number_format($summary['teknisi'] ?? 0) }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-center rounded-4 bg-info bg-opacity-10 text-info"
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Role --}}
    @if ($isManager)
        <div class="alert alert-primary rounded-4 border-0 mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Akses Manajer Operasional
            </div>
            Manajer Operasional hanya dapat melihat data pengguna operasional. Pengelolaan akun dilakukan oleh Admin Operasional.
        </div>
    @endif

    @if ($isAdminOperational)
        <div class="alert alert-info rounded-4 border-0 mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Akses Admin Operasional
            </div>
            Admin Operasional dapat membuat akun Petugas Parkir dan Teknisi Vendor. Password awal akan dibuat otomatis oleh sistem.
        </div>
    @endif

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Filter Pengguna</h5>
                <p class="text-muted small mb-0">
                    Cari berdasarkan NIK, NIP, nama lengkap, email, atau nomor telepon.
                </p>
            </div>

            @if (!empty($search) || !empty($role) || !empty($status))
                <a href="{{ route('user-management.index') }}" class="btn btn-light border rounded-3">
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
                    value="{{ $search }}"
                    class="form-control rounded-3"
                    placeholder="Cari NIK, nama, email, telepon..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select rounded-3">
                    <option value="">Semua Role</option>
                    <option value="petugas" {{ ($role ?? '') === 'petugas' ? 'selected' : '' }}>
                        Petugas Parkir
                    </option>
                    <option value="teknisi" {{ ($role ?? '') === 'teknisi' ? 'selected' : '' }}>
                        Teknisi Vendor
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select rounded-3">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ ($status ?? '') === 'Aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="Tidak Aktif" {{ ($status ?? '') === 'Tidak Aktif' ? 'selected' : '' }}>
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
    </div>

    {{-- Table --}}
    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Pengguna Operasional</h5>
                <p class="text-muted small mb-0">
                    Menampilkan akun Petugas Parkir dan Teknisi Vendor.
                </p>
            </div>

            <div class="text-muted small">
                Filter aktif: <b>{{ $activeFilterCount }}</b>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Pengguna</th>
                        <th>NIK</th>
                        <th>Role</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-end" style="width: 260px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td class="text-muted">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold"
                                         style="width: 42px; height: 42px;">
                                        @if ($item->profile_photo)
                                            <img
                                                src="{{ asset('storage/' . $item->profile_photo) }}"
                                                alt="Foto Profil"
                                                class="rounded-circle"
                                                style="width: 42px; height: 42px; object-fit: cover;"
                                            >
                                        @else
                                            {{ strtoupper(substr($item->full_name ?? $item->name ?? $item->username ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $item->full_name ?? $item->name ?? '-' }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $item->email ?? 'Email belum diisi' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->username ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    NIP: {{ $item->nip ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $roleBadgeClass($item->role ?? '') }}">
                                    {{ $roleLabel($item->role ?? '') }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->phone ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    Kontak pengguna
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($item->status ?? '') }}">
                                    {{ $item->status ?? '-' }}
                                </span>

                                @if ($item->must_change_password ?? false)
                                    <div class="text-warning small mt-1">
                                        <i class="bi bi-key me-1"></i>
                                        Perlu ganti password
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $item->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a href="{{ route('user-management.show', $item) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    @if ($isAdminOperational)
                                        <a href="{{ route('user-management.edit', $item) }}"
                                           class="btn btn-sm btn-warning text-white rounded-3">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('user-management.toggle-status', $item) }}"
                                              onsubmit="return confirm('Yakin ingin mengubah status akun ini?')">
                                            @csrf

                                            <button class="btn btn-sm {{ $item->status === 'Aktif' ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-3">
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
                            <td colspan="8">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                    <h6 class="fw-bold mb-1">Belum ada data pengguna</h6>

                                    <p class="mb-3">
                                        @if ($isAdminOperational)
                                            Silakan buat akun Petugas Parkir atau Teknisi Vendor terlebih dahulu.
                                        @else
                                            Belum ada data pengguna operasional yang dapat ditampilkan.
                                        @endif
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