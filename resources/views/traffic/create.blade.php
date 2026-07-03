@extends('layouts.app')

@section('title', 'Input Traffic Harian | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Input Traffic Harian')
@section('page_subtitle', 'Catat data kendaraan, transaksi, dan pendapatan parkir')

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

    .metric-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
        height: 100%;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 20px;
    }

    .metric-icon.primary { background: #eaf3ff; color: #0d6efd; }
    .metric-icon.success { background: #e7f7ee; color: #198754; }
    .metric-icon.warning { background: #fff6dc; color: #d99a00; }
    .metric-icon.info { background: #e5f8ff; color: #0bb4d8; }

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

    .help-text {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    textarea.form-control {
        min-height: 132px;
    }

    .input-group-text {
        border-radius: 13px 0 0 13px;
        border: 1px solid #d7e3f7;
        background: #eaf3ff;
        color: #071b4d;
        font-weight: 850;
    }

    .input-group .form-control {
        border-radius: 0 13px 13px 0;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-plus-circle"></i>
            </div>

            <div>
                <h3 class="form-page-title">Input Traffic Harian</h3>
                <p class="form-page-subtitle">
                    Input data kendaraan, transaksi, pendapatan, dan kondisi operasional parkir harian.
                </p>
            </div>
        </div>

        <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('traffic-reports.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($location)
            <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Laporan Traffic</h5>
                        <p class="section-subtitle-local">
                            Lokasi otomatis mengikuti lokasi operasional akun Petugas.
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
                                    Data traffic akan disimpan pada lokasi operasional ini.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!$location)
                        <div class="alert alert-danger">
                            Lokasi operasional akun Anda belum ditentukan. Silakan hubungi Admin Operasional.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Laporan <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                name="report_date"
                                value="{{ old('report_date', now()->format('Y-m-d')) }}"
                                class="form-control @error('report_date') is-invalid @enderror"
                                required
                            >
                            @error('report_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Shift <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select @error('shift') is-invalid @enderror" required>
                                <option value="">Pilih Shift</option>
                                <option value="Pagi" {{ old('shift') === 'Pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="Siang" {{ old('shift') === 'Siang' ? 'selected' : '' }}>Siang</option>
                                <option value="Malam" {{ old('shift') === 'Malam' ? 'selected' : '' }}>Malam</option>
                            </select>
                            @error('shift')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Data Kendaraan</h5>
                        <p class="section-subtitle-local">
                            Isi jumlah kendaraan masuk, keluar, dan rincian kategori kendaraan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Kendaraan Masuk <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_vehicle_in" value="{{ old('total_vehicle_in', 0) }}" class="form-control @error('total_vehicle_in') is-invalid @enderror" required>
                            @error('total_vehicle_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Kendaraan Keluar <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_vehicle_out" value="{{ old('total_vehicle_out', 0) }}" class="form-control @error('total_vehicle_out') is-invalid @enderror" required>
                            @error('total_vehicle_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobil</label>
                            <input type="number" min="0" name="car_count" value="{{ old('car_count', 0) }}" class="form-control @error('car_count') is-invalid @enderror">
                            @error('car_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Motor</label>
                            <input type="number" min="0" name="motorcycle_count" value="{{ old('motorcycle_count', 0) }}" class="form-control @error('motorcycle_count') is-invalid @enderror">
                            @error('motorcycle_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kendaraan Lainnya</label>
                            <input type="number" min="0" name="other_vehicle_count" value="{{ old('other_vehicle_count', 0) }}" class="form-control @error('other_vehicle_count') is-invalid @enderror">
                            @error('other_vehicle_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Transaksi dan Pendapatan</h5>
                        <p class="section-subtitle-local">
                            Isi jumlah transaksi dan total pendapatan parkir pada shift tersebut.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Transaksi <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_transaction" value="{{ old('total_transaction', 0) }}" class="form-control @error('total_transaction') is-invalid @enderror" required>
                            @error('total_transaction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Pendapatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="total_revenue" value="{{ old('total_revenue', 0) }}" class="form-control @error('total_revenue') is-invalid @enderror" required>
                            </div>
                            @error('total_revenue') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Dokumentasi dan Catatan</h5>
                        <p class="section-subtitle-local">
                            Tambahkan foto dan catatan jika diperlukan sebagai bukti operasional.
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Dokumentasi</label>
                        <div class="upload-box">
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <div class="help-text">
                                Format gambar: JPG, JPEG, PNG. Gunakan foto yang jelas jika ada dokumentasi operasional.
                            </div>
                            @error('photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Tulis catatan tambahan jika ada kondisi khusus di lapangan...">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="action-card sticky-top" style="top: 120px;">
                    <h5 class="section-title-local">Ringkasan Input</h5>
                    <p class="section-subtitle-local mb-4">
                        Pastikan data sudah benar sebelum disimpan.
                    </p>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Tanggal dan Shift</div>
                            <div class="small text-muted fw-semibold">Sesuaikan dengan waktu operasional.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Data Kendaraan</div>
                            <div class="small text-muted fw-semibold">Pastikan jumlah kendaraan masuk/keluar benar.</div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Pendapatan</div>
                            <div class="small text-muted fw-semibold">Input nominal tanpa titik atau koma.</div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-4 flex-fill">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4 flex-fill" {{ !$location ? 'disabled' : '' }}>
                            <i class="bi bi-save me-1"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection