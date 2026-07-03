@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Tambah Akun Pengguna')
@section('page_subtitle', 'Pembuatan akun Petugas Parkir dan Teknisi Vendor')

@section('content')
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

    .guide-card {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .guide-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .guide-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .guide-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 3px;
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
                <i class="bi bi-person-plus"></i>
            </div>

            <div>
                <h3 class="page-title-local">Tambah Akun Pengguna</h3>
                <p class="page-subtitle-local">
                    Buat akun baru untuk Petugas Parkir atau Teknisi Vendor menggunakan NIK sebagai username login.
                </p>
            </div>
        </div>

        <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('user-management.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-section-card">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Akun</h5>
                        <p class="section-subtitle-local">
                            Lengkapi data identitas akun, role, lokasi operasional, dan status pengguna.
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
                                value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="Contoh: 1005"
                                autofocus
                                required
                            >

                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                NIK digunakan sebagai username untuk login ke sistem.
                            </span>
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
                                required
                            >

                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Nama pengguna akan tampil pada dashboard dan riwayat aktivitas.
                            </span>
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

                            <span class="field-helper">
                                Boleh dikosongkan jika pengguna belum memiliki email.
                            </span>
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

                            <span class="field-helper">
                                Nomor telepon digunakan untuk kebutuhan koordinasi operasional.
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

                            <span class="field-helper">
                                Admin Operasional hanya membuat akun Petugas Parkir dan Teknisi Vendor.
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
                                        {{ (string) old('parking_location_id') === (string) $location->id ? 'selected' : '' }}
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
                                Akses history Petugas mengikuti lokasi operasional yang dipilih.
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

                            <span class="field-helper">
                                Akun aktif dapat login ke sistem.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box h-100">
                                <div class="fw-bold text-primary mb-1">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    Basis Akses Lokasi
                                </div>

                                <div class="text-muted small fw-semibold">
                                    History laporan kendala, traffic harian, dan backup barang mengikuti lokasi operasional akun.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box warning mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-key-fill me-1"></i>
                                    Password Awal Otomatis
                                </div>

                                Setelah akun dibuat, sistem akan menghasilkan password awal dan menampilkannya kepada Admin Operasional.
                                Pengguna wajib mengganti password setelah login.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-card mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Simpan Akun Pengguna</h6>
                            <p class="text-muted small mb-0">
                                Pastikan NIK, role, dan lokasi operasional sudah benar sebelum akun dibuat.
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('user-management.index') }}" class="btn btn-soft rounded-3 px-4">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-card sticky-top" style="top: 120px;">
                    <div class="guide-card mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="guide-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>

                            <div>
                                <div class="guide-label">Panduan Akun</div>
                                <div class="guide-value">Login Menggunakan NIK</div>
                                <div class="text-muted small fw-semibold">
                                    Username login pengguna mengikuti NIK yang diinput oleh Admin Operasional.
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title-local">Catatan Pembuatan Akun</h5>
                    <p class="section-subtitle-local mb-4">
                        Pastikan role dan lokasi operasional dipilih dengan benar.
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

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div>
                            <div class="fw-bold text-dark">Lokasi Operasional</div>
                            <div class="text-muted small fw-semibold">
                                Lokasi menentukan akses data operasional pengguna.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-key"></i>
                        </div>

                        <div>
                            <div class="fw-bold text-dark">Password Awal</div>
                            <div class="text-muted small fw-semibold">
                                Password awal hanya muncul setelah akun berhasil dibuat.
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning rounded-4 mt-3 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Penting
                        </div>
                        Catat password awal sebelum meninggalkan halaman setelah akun dibuat.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection