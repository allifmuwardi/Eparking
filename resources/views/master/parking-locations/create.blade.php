@extends('layouts.app')

@section('title', 'Tambah Lokasi Parkir | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Tambah Lokasi Parkir')
@section('page_subtitle', 'Input master lokasi operasional parkir')

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

    .example-box {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 14px;
        margin-bottom: 12px;
    }

    .example-box:last-child {
        margin-bottom: 0;
    }

    .example-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        margin-bottom: 3px;
    }

    .example-value {
        color: #071b4d;
        font-size: 14px;
        font-weight: 950;
    }

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    textarea.form-control {
        min-height: 130px;
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
                <i class="bi bi-plus-circle"></i>
            </div>

            <div>
                <h3 class="page-title-local">Tambah Lokasi Parkir</h3>
                <p class="page-subtitle-local">
                    Tambahkan lokasi parkir baru agar dapat digunakan pada modul operasional.
                </p>
            </div>
        </div>

        <a href="{{ route('parking-locations.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('parking-locations.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-section-card">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Lokasi</h5>
                        <p class="section-subtitle-local">
                            Lengkapi kode lokasi, nama lokasi, alamat, area, PIC, dan status lokasi.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Kode Lokasi <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="location_code"
                                value="{{ old('location_code') }}"
                                class="form-control @error('location_code') is-invalid @enderror"
                                placeholder="Contoh: LPK001"
                                required
                            >

                            @error('location_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Gunakan kode unik untuk lokasi parkir.
                            </span>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">
                                Nama Lokasi <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="location_name"
                                value="{{ old('location_name') }}"
                                class="form-control @error('location_name') is-invalid @enderror"
                                placeholder="Contoh: Area Parkir Utama"
                                required
                            >

                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Nama lokasi yang mudah dikenali petugas dan admin.
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>

                            <textarea
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap lokasi parkir..."
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Alamat membantu identifikasi lokasi saat monitoring dan pelaporan.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Area / Zona</label>

                            <input
                                type="text"
                                name="area"
                                value="{{ old('area') }}"
                                class="form-control @error('area') is-invalid @enderror"
                                placeholder="Contoh: Gate Utama / Basement / Outdoor"
                            >

                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Isi area detail di dalam lokasi parkir.
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
                                Lokasi aktif akan muncul di pilihan form operasional.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama PIC</label>

                            <input
                                type="text"
                                name="pic_name"
                                value="{{ old('pic_name') }}"
                                class="form-control @error('pic_name') is-invalid @enderror"
                                placeholder="Nama penanggung jawab lokasi"
                            >

                            @error('pic_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                PIC digunakan sebagai referensi penanggung jawab lokasi.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Telepon PIC</label>

                            <input
                                type="text"
                                name="pic_phone"
                                value="{{ old('pic_phone') }}"
                                class="form-control @error('pic_phone') is-invalid @enderror"
                                placeholder="Contoh: 081234567890"
                            >

                            @error('pic_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Nomor kontak digunakan jika dibutuhkan koordinasi lokasi.
                            </span>
                        </div>
                    </div>
                </div>

                <div class="action-card mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Simpan Lokasi Parkir</h6>
                            <p class="text-muted small mb-0">
                                Pastikan data lokasi sudah benar sebelum disimpan.
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('parking-locations.index') }}" class="btn btn-soft rounded-3 px-4">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Lokasi
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
                                <div class="guide-label">Panduan Lokasi</div>
                                <div class="guide-value">Data Dasar Operasional</div>
                                <div class="text-muted small fw-semibold">
                                    Lokasi dengan status Aktif dapat digunakan pada laporan kendala,
                                    traffic harian, dan permintaan backup barang.
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title-local">Contoh Pengisian</h5>
                    <p class="section-subtitle-local mb-3">
                        Gunakan format data yang konsisten agar mudah dibaca.
                    </p>

                    <div class="example-box">
                        <div class="example-label">Kode Lokasi</div>
                        <div class="example-value">LPK001</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Nama Lokasi</div>
                        <div class="example-value">Area Parkir Utama</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Area / Zona</div>
                        <div class="example-value">Basement / Gate Keluar</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Status</div>
                        <div class="example-value">
                            <span class="badge rounded-pill bg-success">Aktif</span>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-warning rounded-4 mb-0">
                        Untuk lokasi yang sudah pernah digunakan pada transaksi, lebih aman ubah status menjadi
                        <b>Tidak Aktif</b> daripada menghapus data.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection