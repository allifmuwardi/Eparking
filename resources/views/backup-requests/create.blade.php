@extends('layouts.app')

@section('title', 'Ajukan Permintaan Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Ajukan Permintaan Backup')
@section('page_subtitle', 'Input kebutuhan barang backup operasional parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $locations->first() ?? $authUser->parkingLocation ?? null;

    $backupItems = $backupItems ?? $items ?? collect();

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->location_code)) {
            $locationLabel .= ' (' . $location->location_code . ')';
        }
    }
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
        margin-bottom: 0;
        line-height: 1.55;
    }

    .header-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #fff;
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
        background: #fff;
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
        color: #fff;
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
    .note-icon.info { background: #e5f8ff; color: #0bb4d8; }

    .priority-box {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 14px;
        margin-bottom: 10px;
    }

    textarea.form-control {
        min-height: 150px;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3 class="page-title-local">Ajukan Permintaan Backup Barang</h3>
                <p class="page-subtitle-local">
                    Ajukan kebutuhan barang backup untuk mendukung operasional parkir.
                </p>
            </div>
        </div>

        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('backup-requests.store') }}">
        @csrf

        @if ($location)
            <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Lokasi</h5>
                        <p class="section-subtitle-local">
                            Lokasi permintaan otomatis mengikuti lokasi operasional akun Petugas.
                        </p>
                    </div>

                    <div class="location-box">
                        <div class="d-flex align-items-start gap-3">
                            <div class="location-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <div class="location-label">Lokasi Operasional</div>
                                <div class="location-value">{{ $locationLabel }}</div>
                                <div class="text-muted small fw-semibold">
                                    Permintaan backup akan tercatat pada lokasi operasional ini.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!$location)
                        <div class="alert alert-danger rounded-4 border-0 mt-3">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Lokasi Operasional Belum Ditentukan
                            </div>
                            Akun Anda belum memiliki lokasi operasional aktif. Silakan hubungi Admin Operasional.
                        </div>
                    @endif
                </div>

                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Detail Barang Backup</h5>
                        <p class="section-subtitle-local">
                            Pilih barang backup yang dibutuhkan beserta jumlah dan prioritas pengajuan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Barang Backup <span class="text-danger">*</span></label>
                            <select name="backup_item_id" class="form-select @error('backup_item_id') is-invalid @enderror" {{ !$location ? 'disabled' : '' }} required>
                                <option value="">Pilih Barang Backup</option>
                                @foreach ($backupItems as $item)
                                    <option value="{{ $item->id }}" {{ old('backup_item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->item_name }} — Stok: {{ number_format($item->stock ?? 0) }} {{ $item->unit ?? 'unit' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('backup_item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text fw-semibold">
                                Hanya barang yang tersedia dan memiliki stok yang dapat diajukan.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                min="1"
                                name="quantity"
                                value="{{ old('quantity', 1) }}"
                                class="form-control @error('quantity') is-invalid @enderror"
                                {{ !$location ? 'disabled' : '' }}
                                required
                            >
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" {{ !$location ? 'disabled' : '' }} required>
                                <option value="Rendah" {{ old('priority') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Sedang" {{ old('priority', 'Sedang') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ old('priority') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="Darurat" {{ old('priority') === 'Darurat' ? 'selected' : '' }}>Darurat</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alasan Kebutuhan <span class="text-danger">*</span></label>
                            <textarea
                                name="reason"
                                class="form-control @error('reason') is-invalid @enderror"
                                placeholder="Jelaskan alasan kebutuhan barang backup, kondisi operasional, dan dampak jika barang tidak tersedia..."
                                {{ !$location ? 'disabled' : '' }}
                                required
                            >{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text fw-semibold">
                                Alasan yang jelas membantu Manajer dalam proses verifikasi.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Keterangan Prioritas</h5>
                        <p class="section-subtitle-local">
                            Gunakan prioritas sesuai tingkat kebutuhan operasional.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="priority-box h-100">
                                <span class="badge rounded-pill bg-success mb-2">Rendah</span>
                                <div class="small fw-semibold text-muted">Kebutuhan tidak mendesak dan tidak mengganggu operasional utama.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="priority-box h-100">
                                <span class="badge rounded-pill bg-primary mb-2">Sedang</span>
                                <div class="small fw-semibold text-muted">Dibutuhkan untuk mendukung aktivitas operasional normal.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="priority-box h-100">
                                <span class="badge rounded-pill bg-warning text-dark mb-2">Tinggi</span>
                                <div class="small fw-semibold text-muted">Berdampak cukup besar jika barang tidak segera tersedia.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="priority-box h-100">
                                <span class="badge rounded-pill bg-danger mb-2">Darurat</span>
                                <div class="small fw-semibold text-muted">Sangat mendesak karena berpotensi menghambat operasional parkir.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="action-card sticky-top" style="top: 120px;">
                    <h5 class="section-title-local">Ringkasan Pengajuan</h5>
                    <p class="section-subtitle-local mb-4">
                        Pastikan barang, jumlah, dan alasan kebutuhan sudah benar.
                    </p>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-box"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Pilih Barang</div>
                            <div class="small text-muted fw-semibold">Pilih barang backup yang tersedia di master barang.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-123"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Jumlah Dibutuhkan</div>
                            <div class="small text-muted fw-semibold">Pastikan jumlah sesuai kebutuhan operasional.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Alasan Kebutuhan</div>
                            <div class="small text-muted fw-semibold">Tuliskan alasan yang jelas agar mudah diverifikasi.</div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info rounded-4">
                        <div class="fw-bold mb-1">Status Awal</div>
                        Setelah disimpan, status permintaan menjadi <b>Menunggu Verifikasi</b>.
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-4 flex-fill">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4 flex-fill" {{ !$location ? 'disabled' : '' }}>
                            <i class="bi bi-send me-1"></i>
                            Ajukan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection