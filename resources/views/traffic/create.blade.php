@extends('layouts.app')

@section('title', 'Input Traffic Harian | Sistem Penanganan Kendala Parkir')

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
        min-height: 140px;
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

    .metric-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .metric-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .metric-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .metric-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
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

<div class="container-fluid">
    {{-- Header --}}
    <div class="form-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-car-front"></i>
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
            {{-- Form Utama --}}
            <div class="col-lg-8">
                {{-- Informasi Laporan --}}
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title">Informasi Laporan Traffic</h5>
                        <p class="section-subtitle">
                            Lokasi otomatis mengikuti lokasi operasional akun Petugas.
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
                                    Traffic harian akan tercatat pada lokasi operasional akun Anda.
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
                                Tanggal Laporan <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="report_date"
                                value="{{ old('report_date', date('Y-m-d')) }}"
                                class="form-control @error('report_date') is-invalid @enderror"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('report_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Tanggal operasional traffic.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Shift <span class="text-danger">*</span>
                            </label>

                            <select
                                name="shift"
                                class="form-select @error('shift') is-invalid @enderror"
                                {{ !$location ? 'disabled' : '' }}
                            >
                                <option value="Pagi" {{ old('shift') === 'Pagi' ? 'selected' : '' }}>
                                    Pagi
                                </option>
                                <option value="Siang" {{ old('shift') === 'Siang' ? 'selected' : '' }}>
                                    Siang
                                </option>
                                <option value="Malam" {{ old('shift') === 'Malam' ? 'selected' : '' }}>
                                    Malam
                                </option>
                            </select>

                            @error('shift')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Shift petugas saat laporan dibuat.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Traffic --}}
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title">Data Traffic Kendaraan</h5>
                        <p class="section-subtitle">
                            Isi jumlah kendaraan masuk, keluar, transaksi, dan kategori kendaraan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="metric-box">
                                <div class="metric-icon primary">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>

                                <label class="form-label">
                                    Kendaraan Masuk <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="total_vehicle_in"
                                    value="{{ old('total_vehicle_in', 0) }}"
                                    class="form-control @error('total_vehicle_in') is-invalid @enderror"
                                    min="0"
                                    {{ !$location ? 'disabled' : '' }}
                                >

                                @error('total_vehicle_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="help-text">Total kendaraan masuk.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-box">
                                <div class="metric-icon success">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>

                                <label class="form-label">
                                    Kendaraan Keluar <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="total_vehicle_out"
                                    value="{{ old('total_vehicle_out', 0) }}"
                                    class="form-control @error('total_vehicle_out') is-invalid @enderror"
                                    min="0"
                                    {{ !$location ? 'disabled' : '' }}
                                >

                                @error('total_vehicle_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="help-text">Total kendaraan keluar.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-box">
                                <div class="metric-icon warning">
                                    <i class="bi bi-receipt"></i>
                                </div>

                                <label class="form-label">
                                    Total Transaksi <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="total_transaction"
                                    value="{{ old('total_transaction', 0) }}"
                                    class="form-control @error('total_transaction') is-invalid @enderror"
                                    min="0"
                                    {{ !$location ? 'disabled' : '' }}
                                >

                                @error('total_transaction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="help-text">Jumlah transaksi parkir.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Jumlah Mobil <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="car_count"
                                value="{{ old('car_count', 0) }}"
                                class="form-control @error('car_count') is-invalid @enderror"
                                min="0"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('car_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">Jumlah kendaraan roda empat.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Jumlah Motor <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="motorcycle_count"
                                value="{{ old('motorcycle_count', 0) }}"
                                class="form-control @error('motorcycle_count') is-invalid @enderror"
                                min="0"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('motorcycle_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">Jumlah kendaraan roda dua.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Kendaraan Lain <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="other_vehicle_count"
                                value="{{ old('other_vehicle_count', 0) }}"
                                class="form-control @error('other_vehicle_count') is-invalid @enderror"
                                min="0"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('other_vehicle_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">Bus, truk, atau kategori lainnya.</div>
                        </div>
                    </div>
                </div>

                {{-- Pendapatan dan Dokumentasi --}}
                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title">Pendapatan dan Dokumentasi</h5>
                        <p class="section-subtitle">
                            Isi total pendapatan dan catatan kondisi operasional jika diperlukan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Total Pendapatan <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>

                                <input
                                    type="number"
                                    name="total_revenue"
                                    value="{{ old('total_revenue', 0) }}"
                                    class="form-control @error('total_revenue') is-invalid @enderror"
                                    min="0"
                                    step="100"
                                    {{ !$location ? 'disabled' : '' }}
                                >

                                @error('total_revenue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="help-text">
                                Isi angka saja. Contoh: 1500000.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Foto Dokumentasi</label>

                            <div class="upload-box">
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
                                    Format JPG, JPEG, PNG. Maksimal 2 MB.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Catatan Operasional</label>

                            <textarea
                                name="notes"
                                rows="5"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Masukkan catatan kondisi traffic, kendala antrean, cuaca, kepadatan kendaraan, atau kondisi operasional lainnya..."
                                {{ !$location ? 'disabled' : '' }}
                            >{{ old('notes') }}</textarea>

                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                Traffic akan tercatat pada lokasi operasional akun Anda.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon info">
                            <i class="bi bi-2-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Isi data traffic</div>
                            <div class="text-muted small">
                                Masukkan jumlah kendaraan masuk, keluar, transaksi, dan kategori kendaraan.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-3-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Tambahkan catatan</div>
                            <div class="text-muted small">
                                Catatan membantu Manajer membaca kondisi operasional harian.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Validasi Duplikasi</h5>

                    <div class="alert alert-primary rounded-4 border-0 mb-0">
                        Sistem akan menolak jika sudah ada laporan traffic pada
                        <b>lokasi operasional, tanggal, dan shift</b> yang sama.
                    </div>
                </div>

                <div class="page-card p-4">
                    <h5 class="section-title mb-3">Keterangan Data</h5>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Kendaraan Masuk / Keluar</div>
                            <div class="text-muted small">
                                Jumlah kendaraan yang masuk dan keluar area parkir selama shift berlangsung.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Total Transaksi</div>
                            <div class="text-muted small">
                                Jumlah transaksi parkir yang tercatat pada shift tersebut.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Total Pendapatan</div>
                            <div class="text-muted small">
                                Total nominal pendapatan parkir pada periode shift tersebut.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="action-card mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Simpan Traffic Harian</h6>
                    <p class="text-muted small mb-0">
                        Pastikan data traffic sudah benar sebelum disimpan.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4" {{ !$location ? 'disabled' : '' }}>
                        <i class="bi bi-save me-1"></i>
                        Simpan Traffic
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection