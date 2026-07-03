@extends('layouts.app')

@section('title', 'Edit Permintaan Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Edit Permintaan Backup')
@section('page_subtitle', 'Perbarui pengajuan backup barang sebelum diverifikasi')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $backupRequest->parkingLocation ?? $locations->first() ?? $authUser->parkingLocation ?? null;

    $backupItems = $backupItems ?? $items ?? collect();

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->location_code)) {
            $locationLabel .= ' (' . $location->location_code . ')';
        }
    }

    $statusBadgeClass = match ($backupRequest->status ?? '') {
        'Menunggu Verifikasi' => 'bg-warning text-dark',
        'Disetujui' => 'bg-success',
        'Ditolak' => 'bg-danger',
        'Dalam Proses' => 'bg-info text-dark',
        'Selesai' => 'bg-primary',
        default => 'bg-secondary',
    };
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
        background: linear-gradient(145deg, #ffc107, #ef9f00);
        color: #fff;
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

    textarea.form-control {
        min-height: 150px;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h3 class="page-title-local">Edit Permintaan Backup Barang</h3>
                <p class="page-subtitle-local">
                    Perbarui pengajuan selama status masih Menunggu Verifikasi.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('backup-requests.show', $backupRequest) }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>

            <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('backup-requests.update', $backupRequest) }}">
        @csrf
        @method('PUT')

        @if ($location)
            <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Lokasi</h5>
                        <p class="section-subtitle-local">
                            Lokasi permintaan mengikuti lokasi operasional akun Petugas.
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
                                    Permintaan backup tetap tercatat pada lokasi operasional ini.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Detail Barang Backup</h5>
                        <p class="section-subtitle-local">
                            Perbarui barang, jumlah, prioritas, dan alasan kebutuhan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Barang Backup <span class="text-danger">*</span></label>
                            <select name="backup_item_id" class="form-select @error('backup_item_id') is-invalid @enderror" required>
                                <option value="">Pilih Barang Backup</option>
                                @foreach ($backupItems as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('backup_item_id', $backupRequest->backup_item_id) == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->item_name }} — Stok: {{ number_format($item->stock ?? 0) }} {{ $item->unit ?? 'unit' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('backup_item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                min="1"
                                name="quantity"
                                value="{{ old('quantity', $backupRequest->quantity ?? 1) }}"
                                class="form-control @error('quantity') is-invalid @enderror"
                                required
                            >
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="Rendah" {{ old('priority', $backupRequest->priority) === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Sedang" {{ old('priority', $backupRequest->priority) === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ old('priority', $backupRequest->priority) === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="Darurat" {{ old('priority', $backupRequest->priority) === 'Darurat' ? 'selected' : '' }}>Darurat</option>
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
                                placeholder="Jelaskan alasan kebutuhan barang backup..."
                                required
                            >{{ old('reason', $backupRequest->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="action-card sticky-top" style="top: 120px;">
                    <h5 class="section-title-local">Ringkasan Data</h5>
                    <p class="section-subtitle-local mb-3">
                        Permintaan hanya dapat diedit sebelum diverifikasi Manajer.
                    </p>

                    <div class="summary-row">
                        <div class="summary-label">No. Request</div>
                        <div class="summary-value">{{ $backupRequest->request_number ?? '-' }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Status</div>
                        <div class="summary-value">
                            <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                {{ $backupRequest->status ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Dibuat</div>
                        <div class="summary-value">{{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Terakhir Update</div>
                        <div class="summary-value">{{ $backupRequest->updated_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-4 flex-fill">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary rounded-3 px-4 flex-fill">
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