@extends('layouts.app')

@section('title', 'Profil Saya | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $roleLabel = match ($user->role) {
        'petugas' => 'Petugas Parkir',
        'teknisi' => 'Teknisi Vendor',
        'manajer' => 'Manajer Operasional',
        'admin' => 'Admin Operasional',
        default => 'Pengguna',
    };

    $roleBadgeClass = match ($user->role) {
        'petugas' => 'bg-primary',
        'teknisi' => 'bg-info text-dark',
        'manajer' => 'bg-warning text-dark',
        'admin' => 'bg-success',
        default => 'bg-secondary',
    };

    $statusBadgeClass = match ($user->status) {
        'Aktif' => 'bg-success',
        'Tidak Aktif' => 'bg-secondary',
        default => 'bg-secondary',
    };

    $displayName = $user->full_name ?? $user->name ?? 'User';
    $initial = strtoupper(substr($displayName, 0, 1));
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-person-circle fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Profil Saya</h3>
                    <p class="text-muted mb-0">
                        Kelola informasi profil, foto profil, dan password akun Anda.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>

    @if ($user->must_change_password ?? false)
        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-white"
                     style="width: 42px; height: 42px;">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <div class="fw-bold mb-1">Password Perlu Diganti</div>
                    <div>
                        Akun Anda masih menggunakan password awal atau password hasil reset.
                        Silakan ganti password pada form <b>Ganti Password</b> di halaman ini.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">

        {{-- KIRI --}}
        <div class="col-lg-4">
            <div class="page-card p-4 mb-4">
                <div class="text-center">
                    <div class="mx-auto rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold mb-3"
                         style="width: 118px; height: 118px; font-size: 44px; overflow: hidden;">
                        @if ($user->profile_photo)
                            <img
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                alt="Foto Profil"
                                style="width: 118px; height: 118px; object-fit: cover;"
                            >
                        @else
                            {{ $initial }}
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1">{{ $displayName }}</h4>

                    <div class="text-muted mb-3">
                        NIK: {{ $user->username ?? '-' }}
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
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

                <hr>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th class="text-muted small ps-0">Nama</th>
                        <td class="text-end fw-semibold">{{ $displayName }}</td>
                    </tr>

                    <tr>
                        <th class="text-muted small ps-0">NIK</th>
                        <td class="text-end fw-semibold">{{ $user->username ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-muted small ps-0">Email</th>
                        <td class="text-end">{{ $user->email ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-muted small ps-0">Telepon</th>
                        <td class="text-end">{{ $user->phone ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-muted small ps-0">Role</th>
                        <td class="text-end">{{ $roleLabel }}</td>
                    </tr>

                    <tr>
                        <th class="text-muted small ps-0">Bergabung</th>
                        <td class="text-end">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="page-card p-4">
                <h5 class="fw-bold mb-3">Informasi Akun</h5>

                <div class="d-flex gap-3 mb-3">
                    <div class="text-primary">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Login Menggunakan NIK</div>
                        <div class="text-muted small">
                            Akun ini login menggunakan NIK: {{ $user->username ?? '-' }}.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="text-success">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Role Akun</div>
                        <div class="text-muted small">
                            Hak akses Anda adalah {{ $roleLabel }}.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="text-warning">
                        <i class="bi bi-lock fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Keamanan Password</div>
                        <div class="text-muted small">
                            Gunakan password yang kuat dan jangan berikan ke orang lain.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="col-lg-8">

            {{-- UPDATE PROFIL --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Update Profil</h5>
                    <p class="text-muted small mb-0">
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
                                class="form-control rounded-3 @error('full_name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap"
                            >
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input
                                type="text"
                                value="{{ $user->username ?? '-' }}"
                                class="form-control rounded-3"
                                readonly
                            >
                            <small class="text-muted">
                                NIK hanya dapat diubah oleh Admin Operasional.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control rounded-3 @error('email') is-invalid @enderror"
                                placeholder="nama@email.com"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                placeholder="Contoh: 08123456789"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Foto Profil</label>
                            <input
                                type="file"
                                name="profile_photo"
                                class="form-control rounded-3 @error('profile_photo') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/jpg"
                            >
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                        <div>
                            @if ($user->profile_photo)
                                <form method="POST" action="{{ route('profile.photo.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary rounded-3">
                            <i class="bi bi-save me-1"></i>
                            Simpan Profil
                        </button>
                    </div>
                </form>

                @if ($user->profile_photo)
                    <form method="POST"
                          action="{{ route('profile.photo.delete') }}"
                          class="mt-3"
                          onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline-danger rounded-3">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Foto Profil
                        </button>
                    </form>
                @endif
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Ganti Password</h5>
                    <p class="text-muted small mb-0">
                        Ubah password akun Anda secara mandiri.
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
                                class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan password saat ini"
                                autocomplete="current-password"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control rounded-3"
                                placeholder="Ulangi password baru"
                                autocomplete="new-password"
                            >
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info rounded-4 border-0 mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-shield-lock-fill me-1"></i>
                                    Tips Password
                                </div>
                                Gunakan minimal 8 karakter dan kombinasikan huruf, angka, atau simbol agar lebih aman.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-warning text-white rounded-3">
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