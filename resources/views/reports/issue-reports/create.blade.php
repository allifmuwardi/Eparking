@extends('layouts.app')

@section('title', 'Buat Laporan Kendala | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Buat Laporan Kendala')
@section('page_subtitle', 'Input laporan kendala operasional parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $locations->first() ?? $authUser->parkingLocation ?? null;

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->location_code)) {
            $locationLabel .= ' (' . $location->location_code . ')';
        }
    }
@endphp

<style>
    .form-page-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .form-page-subtitle {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.55;
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
        margin-bottom: 0;
        line-height: 1.5;
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
        font-weight: 900;
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

    .upload-box {
        border-radius: 18px;
        border: 1px dashed #b9cbea;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 22px;
    }

    .help-text {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    textarea.form-control {
        min-height: 160px;
    }

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
        padding: 20px;
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

    .note-icon.primary { background: #eaf3ff; color: #0d6efd; }
    .note-icon.success { background: #e7f7ee; color: #198754; }
    .note-icon.warning { background: #fff6dc; color: #d99a00; }
    .note-icon.danger { background: #fde8e8; color: #dc3545; }

    .priority-card {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 14px;
        margin-bottom: 10px;
    }

    .priority-card:last-child {
        margin-bottom: 0;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
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
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Kendala</h5>
                        <p class="section-subtitle-local">
                            Lengkapi informasi utama kendala yang ditemukan di area parkir.
                        </p>
                    </div>

                    <div class="location-box mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="location-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <div class="location-label">Lokasi Operasional</div>
                                <div class="location-value">{{ $locationLabel }}</div>
                                <div class="text-muted small fw-semibold">
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
                            <label class="form-label">Kategori Kendala <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" {{ !$location ? 'disabled' : '' }} required>
                                <option value="">Pilih Kategori</option>
                                <option value="Perangkat" {{ old('category') === 'Perangkat' ? 'selected' : '' }}>Perangkat</option>
                                <option value="Sistem" {{ old('category') === 'Sistem' ? 'selected' : '' }}>Sistem</option>
                                <option value="Tiket" {{ old('category') === 'Tiket' ? 'selected' : '' }}>Tiket</option>
                                <option value="Pembayaran" {{ old('category') === 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                <option value="Jaringan" {{ old('category') === 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                <option value="Lainnya" {{ old('category') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="help-text">Tentukan jenis kendala agar mudah diproses.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" {{ !$location ? 'disabled' : '' }} required>
                                <option value="Rendah" {{ old('priority') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Sedang" {{ old('priority', 'Sedang') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ old('priority') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="Darurat" {{ old('priority') === 'Darurat' ? 'selected' : '' }}>Darurat</option>
                            </select>
                            @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="help-text">Pilih tingkat urgensi kendala.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Judul Kendala <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Contoh: Barrier Gate Tidak Terbuka"
                                {{ !$location ? 'disabled' : '' }}
                                required
                            >
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="help-text">Buat judul singkat dan jelas.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi Kendala <span class="text-danger">*</span></label>
                            <textarea
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Jelaskan kronologi kendala, kondisi perangkat, lokasi detail, dan dampak terhadap operasional parkir..."
                                {{ !$location ? 'disabled' : '' }}
                                required
                            >{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="help-text">
                                Contoh: Barrier gate tidak terbuka saat kendaraan keluar, menyebabkan antrean kendaraan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Dokumentasi Kendala</h5>
                        <p class="section-subtitle-local">
                            Lampirkan foto bukti agar Manajer dan Teknisi lebih mudah memahami kondisi di lapangan.
                        </p>
                    </div>

                    <div class="upload-box">
                        <label class="form-label">Upload Foto Bukti</label>
                        <input
                            type="file"
                            name="photo"
                            class="form-control @error('photo') is-invalid @enderror"
                            accept="image/png,image/jpeg,image/jpg"
                            {{ !$location ? 'disabled' : '' }}
                        >
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="help-text">
                            Format yang diperbolehkan: JPG, JPEG, PNG. Gunakan foto yang jelas jika tersedia.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="action-card sticky-top" style="top: 120px;">
                    <h5 class="section-title-local">Panduan Pengisian</h5>
                    <p class="section-subtitle-local mb-4">
                        Pastikan laporan mudah dipahami agar proses verifikasi dan penanganan lebih cepat.
                    </p>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-card-heading"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Judul Singkat</div>
                            <div class="small text-muted fw-semibold">Gunakan judul yang langsung menjelaskan kendala.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Deskripsi Jelas</div>
                            <div class="small text-muted fw-semibold">Tuliskan kronologi, lokasi detail, dan dampak kendala.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-image"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Foto Bukti</div>
                            <div class="small text-muted fw-semibold">Lampirkan foto agar teknisi lebih mudah mengecek kendala.</div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="section-title-local mb-3">Keterangan Prioritas</h5>

                    <div class="priority-card">
                        <span class="badge rounded-pill bg-success mb-2">Rendah</span>
                        <div class="small fw-semibold text-muted">Tidak mengganggu operasional utama.</div>
                    </div>

                    <div class="priority-card">
                        <span class="badge rounded-pill bg-primary mb-2">Sedang</span>
                        <div class="small fw-semibold text-muted">Mengganggu sebagian aktivitas operasional.</div>
                    </div>

                    <div class="priority-card">
                        <span class="badge rounded-pill bg-warning text-dark mb-2">Tinggi</span>
                        <div class="small fw-semibold text-muted">Berdampak besar pada pelayanan parkir.</div>
                    </div>

                    <div class="priority-card">
                        <span class="badge rounded-pill bg-danger mb-2">Darurat</span>
                        <div class="small fw-semibold text-muted">Harus segera ditangani karena menghambat operasional.</div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3 px-4 flex-fill">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4 flex-fill" {{ !$location ? 'disabled' : '' }}>
                            <i class="bi bi-send me-1"></i>
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection