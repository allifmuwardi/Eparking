@extends('layouts.app')

@section('title', 'Edit Traffic Harian | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $trafficReport->parkingLocation ?? $locations->first() ?? null;

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
        background: linear-gradient(145deg, #ffc107, #ef9f00);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 14px 28px rgba(255, 193, 7, 0.26);
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

    .current-photo-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .current-photo-box img {
        max-height: 320px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid #d7e3f7;
    }

    .summary-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
        text-align: center;
        height: 100%;
    }

    .summary-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 21px;
        font-weight: 950;
        margin-bottom: 0;
    }

    .data-table th {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        padding-left: 0;
    }

    .data-table td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 750;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="form-page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h3 class="form-page-title">Edit Traffic Harian</h3>
                <p class="form-page-subtitle">
                    Perbarui data traffic operasional parkir harian jika terdapat kesalahan input.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('traffic-reports.show', $trafficReport) }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>

            <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('traffic-reports.update', $trafficReport) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                            Lokasi traffic tetap mengikuti lokasi operasional akun Petugas.
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
                                    Lokasi tidak dapat diubah dari form edit. Jika lokasi akun salah, Admin Operasional perlu memperbarui akun pengguna.
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
                                value="{{ old('report_date', $trafficReport->report_date?->format('Y-m-d')) }}"
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
                                <option value="Pagi" {{ old('shift', $trafficReport->shift) === 'Pagi' ? 'selected' : '' }}>
                                    Pagi
                                </option>
                                <option value="Siang" {{ old('shift', $trafficReport->shift) === 'Siang' ? 'selected' : '' }}>
                                    Siang
                                </option>
                                <option value="Malam" {{ old('shift', $trafficReport->shift) === 'Malam' ? 'selected' : '' }}>
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
                            Perbarui jumlah kendaraan masuk, keluar, transaksi, dan kategori kendaraan.
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
                                    value="{{ old('total_vehicle_in', $trafficReport->total_vehicle_in) }}"
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
                                    value="{{ old('total_vehicle_out', $trafficReport->total_vehicle_out) }}"
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
                                    value="{{ old('total_transaction', $trafficReport->total_transaction) }}"
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
                                value="{{ old('car_count', $trafficReport->car_count) }}"
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
                                value="{{ old('motorcycle_count', $trafficReport->motorcycle_count) }}"
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
                                value="{{ old('other_vehicle_count', $trafficReport->other_vehicle_count) }}"
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
                            Perbarui total pendapatan, dokumentasi, dan catatan operasional.
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
                                    value="{{ old('total_revenue', $trafficReport->total_revenue) }}"
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
                            <label class="form-label">Ganti Foto Dokumentasi</label>

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

                                @if ($trafficReport->photo)
                                    <div class="text-muted small mt-2">
                                        Foto sebelumnya sudah tersedia. Upload foto baru hanya jika ingin mengganti dokumentasi.
                                    </div>
                                @else
                                    <div class="text-muted small mt-2">
                                        Belum ada foto dokumentasi.
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($trafficReport->photo)
                            <div class="col-md-12">
                                <div class="current-photo-box">
                                    <div class="text-muted small fw-bold mb-2">Foto Dokumentasi Saat Ini</div>
                                    <a href="{{ asset('storage/' . $trafficReport->photo) }}" target="_blank">
                                        <img
                                            src="{{ asset('storage/' . $trafficReport->photo) }}"
                                            alt="Foto Traffic Harian"
                                            class="img-fluid"
                                        >
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <label class="form-label">Catatan Operasional</label>

                            <textarea
                                name="notes"
                                rows="5"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Masukkan catatan kondisi traffic, kendala antrean, cuaca, kepadatan kendaraan, atau kondisi operasional lainnya..."
                                {{ !$location ? 'disabled' : '' }}
                            >{{ old('notes', $trafficReport->notes) }}</textarea>

                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Informasi Data</h5>

                    <table class="table table-borderless align-middle mb-0 data-table">
                        <tr>
                            <th class="ps-0">Petugas</th>
                            <td class="text-end">
                                {{ $trafficReport->user->full_name ?? $trafficReport->user->name ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-0">Lokasi</th>
                            <td class="text-end">
                                {{ $locationLabel }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-0">Dibuat Pada</th>
                            <td class="text-end">
                                {{ $trafficReport->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-0">Diperbarui</th>
                            <td class="text-end">
                                {{ $trafficReport->updated_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Ringkasan Saat Ini</h5>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="summary-box">
                                <div class="summary-label">Masuk</div>
                                <div class="summary-value text-primary">
                                    {{ number_format($trafficReport->total_vehicle_in ?? 0) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="summary-box">
                                <div class="summary-label">Keluar</div>
                                <div class="summary-value text-primary">
                                    {{ number_format($trafficReport->total_vehicle_out ?? 0) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="summary-box" style="background:#e7f7ee;">
                                <div class="summary-label">Pendapatan</div>
                                <div class="summary-value text-success">
                                    Rp {{ number_format($trafficReport->total_revenue ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Catatan Edit</h5>

                    <div class="alert alert-warning rounded-4 border-0 mb-0">
                        Pastikan perubahan data traffic sesuai dengan kondisi operasional sebenarnya.
                        Data ini akan masuk ke laporan rekap dan export Excel.
                    </div>
                </div>

                <div class="page-card p-4">
                    <h5 class="section-title mb-3">Aturan Edit</h5>

                    <div class="note-item">
                        <div class="note-icon primary">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Lokasi Dikunci</div>
                            <div class="text-muted small">
                                Lokasi mengikuti akun Petugas dan tidak dapat diganti bebas.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon success">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Hanya Pembuat</div>
                            <div class="text-muted small">
                                Walaupun history terlihat untuk satu lokasi, edit hanya boleh dilakukan oleh pembuat laporan.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Anti Duplikasi</div>
                            <div class="text-muted small">
                                Sistem menolak traffic ganda pada lokasi, tanggal, dan shift yang sama.
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
                    <h6 class="fw-bold mb-1 text-dark">Update Traffic Harian</h6>
                    <p class="text-muted small mb-0">
                        Simpan perubahan jika seluruh data sudah sesuai.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4" {{ !$location ? 'disabled' : '' }}>
                        <i class="bi bi-save me-1"></i>
                        Update Traffic
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection