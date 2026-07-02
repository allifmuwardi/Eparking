@extends('layouts.app')

@section('title', 'Buat Laporan Kendala | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $locations->first() ?? null;

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->area_zone)) {
            $locationLabel .= ' - ' . $location->area_zone;
        }
    }
@endphp

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
        min-height: 50px;
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

    textarea.form-control {
        min-height: 150px;
    }

    .help-text {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
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

    .location-box {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 38%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .location-icon {
        width: 50px;
        height: 50px;
        border-radius: 17px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .location-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .location-value {
        color: #071b4d;
        font-size: 18px;
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

    .priority-item {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 13px;
        margin-bottom: 10px;
    }

    .upload-box {
        border-radius: 18px;
        border: 1px dashed #b9cbea;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 22px;
    }

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
        padding: 20px;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="form-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-plus-circle"></i>
            </div>

            <div>
                <h3 class="form-page-title">Buat Laporan Kendala</h3>
                <p class="form-page-subtitle">
                    Isi data kendala parkir yang terjadi di lokasi operasional Anda.
                </p>
            </div>
        </div>

        <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('issue-reports.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($location)
            <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
        @endif

        <div class="row g-4">
            {{-- Form Utama --}}
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title">Informasi Kendala</h5>
                        <p class="section-subtitle">
                            Lengkapi informasi utama kendala yang ditemukan di area parkir.
                        </p>
                    </div>

                    {{-- Lokasi Otomatis --}}
                    <div class="location-box mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="location-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <div class="location-label">Lokasi Operasional</div>
                                <div class="location-value">{{ $locationLabel }}</div>
                                <div class="text-muted small">
                                    Lokasi laporan otomatis mengikuti lokasi operasional akun Anda.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!$location)
                        <div class="alert alert-danger rounded-4 border-0">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Lokasi Operasional Belum Ditentukan
                            </div>
                            Akun Anda belum memiliki lokasi operasional aktif. Silakan hubungi Admin Operasional.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Kategori Kendala <span class="text-danger">*</span>
                            </label>

                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    {{ !$location ? 'disabled' : '' }}>
                                <option value="">Pilih Kategori</option>
                                <option value="Perangkat" {{ old('category') === 'Perangkat' ? 'selected' : '' }}>Perangkat</option>
                                <option value="Sistem" {{ old('category') === 'Sistem' ? 'selected' : '' }}>Sistem</option>
                                <option value="Tiket" {{ old('category') === 'Tiket' ? 'selected' : '' }}>Tiket</option>
                                <option value="Pembayaran" {{ old('category') === 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                <option value="Jaringan" {{ old('category') === 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                <option value="Lainnya" {{ old('category') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>

                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Tentukan jenis kendala agar mudah diproses.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Prioritas <span class="text-danger">*</span>
                            </label>

                            <select name="priority"
                                    class="form-select @error('priority') is-invalid @enderror"
                                    {{ !$location ? 'disabled' : '' }}>
                                <option value="Rendah" {{ old('priority') === 'Rendah' ? 'selected' : '' }}>
                                    Rendah
                                </option>
                                <option value="Sedang" {{ old('priority', 'Sedang') === 'Sedang' ? 'selected' : '' }}>
                                    Sedang
                                </option>
                                <option value="Tinggi" {{ old('priority') === 'Tinggi' ? 'selected' : '' }}>
                                    Tinggi
                                </option>
                                <option value="Darurat" {{ old('priority') === 'Darurat' ? 'selected' : '' }}>
                                    Darurat
                                </option>
                            </select>

                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Pilih tingkat urgensi kendala.
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Judul Kendala <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Contoh: Barrier Gate Tidak Terbuka"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Buat judul singkat dan jelas.
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Deskripsi Kendala <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Jelaskan kronologi kendala, kondisi perangkat, lokasi detail, dan dampak terhadap operasional parkir..."
                                {{ !$location ? 'disabled' : '' }}
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Contoh: Barrier gate tidak terbuka saat kendaraan keluar, menyebabkan antrean kendaraan.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dokumentasi --}}
                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title">Dokumentasi Kendala</h5>
                        <p class="section-subtitle">
                            Lampirkan foto bukti agar Manajer dan Teknisi lebih mudah memahami kondisi di lapangan.
                        </p>
                    </div>

                    <div class="upload-box">
                        <label class="form-label">
                            Upload Foto Bukti
                        </label>

                        <input
                            type="file"
                            name="photo"
                            class="form-control @error('photo') is-invalid @enderror"
                            accept="image/png,image/jpeg,image/jpg"
                            {{ !$location ? 'disabled' : '' }}
                        >

                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="text-muted small mt-2">
                            Format yang diperbolehkan: JPG, JPEG, PNG. Ukuran maksimal 2 MB.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Panduan --}}
            <div class="col-lg-4">
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Panduan Pengisian</h5>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-1-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Lokasi otomatis</div>
                            <div class="text-muted small">
                                Laporan akan tercatat pada lokasi operasional akun Anda.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon info">
                            <i class="bi bi-2-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Tulis kendala dengan jelas</div>
                            <div class="text-muted small">
                                Jelaskan kronologi, kondisi perangkat, dan dampak terhadap operasional.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-3-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Lampirkan foto bukti</div>
                            <div class="text-muted small">
                                Foto membantu Manajer dan Teknisi memahami kondisi sebelum penanganan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Informasi Status</h5>

                    <div class="alert alert-primary rounded-4 border-0 mb-0">
                        Setelah laporan dikirim, status awal akan menjadi
                        <b>Menunggu Verifikasi</b> dan Manajer Operasional akan menerima notifikasi.
                    </div>
                </div>

                <div class="page-card p-4">
                    <h5 class="section-title mb-3">Keterangan Prioritas</h5>

                    <div class="priority-item">
                        <span class="badge rounded-pill bg-success">Rendah</span>
                        <div class="text-muted small mt-1">
                            Kendala ringan dan tidak mengganggu operasional utama.
                        </div>
                    </div>

                    <div class="priority-item">
                        <span class="badge rounded-pill bg-primary">Sedang</span>
                        <div class="text-muted small mt-1">
                            Kendala perlu ditangani, namun operasional masih berjalan.
                        </div>
                    </div>

                    <div class="priority-item">
                        <span class="badge rounded-pill bg-warning text-dark">Tinggi</span>
                        <div class="text-muted small mt-1">
                            Kendala berdampak pada kelancaran operasional parkir.
                        </div>
                    </div>

                    <div class="priority-item mb-0">
                        <span class="badge rounded-pill bg-danger">Darurat</span>
                        <div class="text-muted small mt-1">
                            Kendala menghambat operasional dan membutuhkan penanganan segera.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="action-card mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Kirim Laporan Kendala</h6>
                    <p class="text-muted small mb-0">
                        Pastikan seluruh data sudah benar sebelum laporan dikirim.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4" {{ !$location ? 'disabled' : '' }}>
                        <i class="bi bi-send me-1"></i>
                        Kirim Laporan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection