@extends('layouts.app')

@section('title', 'Edit Traffic Harian | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Edit Traffic Harian')
@section('page_subtitle', 'Perbarui data traffic operasional parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $trafficReport->parkingLocation ?? $locations->first() ?? $authUser->parkingLocation ?? null;

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->location_code)) {
            $locationLabel .= ' (' . $location->location_code . ')';
        }
    }

    $reportDateValue = old('report_date');

    if (!$reportDateValue) {
        $reportDateValue = $trafficReport->report_date instanceof \Carbon\Carbon
            ? $trafficReport->report_date->format('Y-m-d')
            : $trafficReport->report_date;
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
        background: linear-gradient(145deg, #ffc107, #ef9f00);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 14px 28px rgba(255, 193, 7, 0.26);
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

    .location-box,
    .current-photo-box,
    .action-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .location-box {
        border-color: #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 38%),
            linear-gradient(180deg, #f8fbff, #ffffff);
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

    .current-photo-box img {
        max-height: 320px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid #d7e3f7;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #edf3fc;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 750;
    }

    .summary-value {
        color: #071b4d;
        font-size: 13px;
        font-weight: 950;
        text-align: right;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h3 class="form-page-title">Edit Traffic Harian</h3>
                <p class="form-page-subtitle">
                    Perbarui data traffic operasional parkir jika terdapat kesalahan input.
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
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Laporan Traffic</h5>
                        <p class="section-subtitle-local">
                            Lokasi laporan mengikuti lokasi operasional akun Petugas.
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
                                    Data traffic akan tetap tersimpan pada lokasi operasional ini.
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
                            <input type="date" name="report_date" value="{{ $reportDateValue }}" class="form-control @error('report_date') is-invalid @enderror" required>
                            @error('report_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Shift <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select @error('shift') is-invalid @enderror" required>
                                <option value="">Pilih Shift</option>
                                <option value="Pagi" {{ old('shift', $trafficReport->shift) === 'Pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="Siang" {{ old('shift', $trafficReport->shift) === 'Siang' ? 'selected' : '' }}>Siang</option>
                                <option value="Malam" {{ old('shift', $trafficReport->shift) === 'Malam' ? 'selected' : '' }}>Malam</option>
                            </select>
                            @error('shift') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Data Kendaraan</h5>
                        <p class="section-subtitle-local">
                            Perbarui jumlah kendaraan masuk, keluar, dan rincian kategori kendaraan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Kendaraan Masuk <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_vehicle_in" value="{{ old('total_vehicle_in', $trafficReport->total_vehicle_in ?? 0) }}" class="form-control @error('total_vehicle_in') is-invalid @enderror" required>
                            @error('total_vehicle_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Kendaraan Keluar <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_vehicle_out" value="{{ old('total_vehicle_out', $trafficReport->total_vehicle_out ?? 0) }}" class="form-control @error('total_vehicle_out') is-invalid @enderror" required>
                            @error('total_vehicle_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobil</label>
                            <input type="number" min="0" name="car_count" value="{{ old('car_count', $trafficReport->car_count ?? 0) }}" class="form-control @error('car_count') is-invalid @enderror">
                            @error('car_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Motor</label>
                            <input type="number" min="0" name="motorcycle_count" value="{{ old('motorcycle_count', $trafficReport->motorcycle_count ?? 0) }}" class="form-control @error('motorcycle_count') is-invalid @enderror">
                            @error('motorcycle_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kendaraan Lainnya</label>
                            <input type="number" min="0" name="other_vehicle_count" value="{{ old('other_vehicle_count', $trafficReport->other_vehicle_count ?? 0) }}" class="form-control @error('other_vehicle_count') is-invalid @enderror">
                            @error('other_vehicle_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Transaksi dan Pendapatan</h5>
                        <p class="section-subtitle-local">
                            Perbarui jumlah transaksi dan total pendapatan parkir.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Transaksi <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="total_transaction" value="{{ old('total_transaction', $trafficReport->total_transaction ?? 0) }}" class="form-control @error('total_transaction') is-invalid @enderror" required>
                            @error('total_transaction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Pendapatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="total_revenue" value="{{ old('total_revenue', $trafficReport->total_revenue ?? 0) }}" class="form-control @error('total_revenue') is-invalid @enderror" required>
                            </div>
                            @error('total_revenue') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Dokumentasi dan Catatan</h5>
                        <p class="section-subtitle-local">
                            Perbarui foto atau catatan tambahan jika diperlukan.
                        </p>
                    </div>

                    @if (!empty($trafficReport->photo))
                        <div class="current-photo-box mb-3">
                            <div class="fw-bold mb-2">Foto Saat Ini</div>
                            <img src="{{ asset('storage/' . $trafficReport->photo) }}" alt="Foto Traffic" class="w-100">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Ganti Foto Dokumentasi</label>
                        <div class="upload-box">
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <div class="help-text">
                                Kosongkan jika tidak ingin mengganti foto.
                            </div>
                            @error('photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Tulis catatan tambahan jika ada...">{{ old('notes', $trafficReport->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="action-card sticky-top" style="top: 120px;">
                    <h5 class="section-title-local">Ringkasan Data</h5>
                    <p class="section-subtitle-local mb-3">
                        Data sebelum atau sesudah perubahan akan tersimpan setelah tombol update ditekan.
                    </p>

                    <div class="summary-row">
                        <div class="summary-label">Pembuat</div>
                        <div class="summary-value">{{ $trafficReport->user->full_name ?? $trafficReport->user->name ?? '-' }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Dibuat</div>
                        <div class="summary-value">{{ $trafficReport->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Terakhir Update</div>
                        <div class="summary-value">{{ $trafficReport->updated_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('traffic-reports.index') }}" class="btn btn-soft rounded-3 px-4 flex-fill">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4 flex-fill" {{ !$location ? 'disabled' : '' }}>
                            <i class="bi bi-save me-1"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection