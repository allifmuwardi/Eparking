@extends('layouts.app')

@section('title', 'Edit Permintaan Backup | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();
    $location = $selectedLocation ?? $backupRequest->parkingLocation ?? $locations->first() ?? null;

    $locationLabel = 'Belum ditentukan';

    if ($location) {
        $locationLabel = $location->location_name ?? '-';

        if (!empty($location->location_code)) {
            $locationLabel .= ' - ' . $location->location_code;
        }
    }

    $statusBadgeClass = match ($backupRequest->status) {
        'Menunggu Verifikasi' => 'bg-warning text-dark',
        'Disetujui' => 'bg-primary',
        'Ditolak' => 'bg-danger',
        'Dalam Proses' => 'bg-info text-dark',
        'Selesai' => 'bg-success',
        default => 'bg-secondary',
    };
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

    .item-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
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

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
        padding: 20px;
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

    .summary-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
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
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 0;
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
                <h3 class="form-page-title">Edit Permintaan Barang Backup</h3>
                <p class="form-page-subtitle">
                    Permintaan hanya dapat diubah selama masih berstatus Menunggu Verifikasi.
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
            {{-- Form Utama --}}
            <div class="col-lg-8">
                <div class="page-card p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="section-title">Informasi Permintaan</h5>
                        <p class="section-subtitle">
                            Lokasi backup tetap mengikuti lokasi operasional akun Petugas.
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
                        <div class="col-md-12">
                            <div class="item-card">
                                <label class="form-label">
                                    Barang Backup <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="backup_item_id"
                                    class="form-select @error('backup_item_id') is-invalid @enderror"
                                    {{ !$location ? 'disabled' : '' }}
                                >
                                    <option value="">Pilih Barang Backup</option>

                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}" {{ old('backup_item_id', $backupRequest->backup_item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->item_code }} - {{ $item->item_name }} | Stok: {{ $item->stock }} {{ $item->unit }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('backup_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="help-text">
                                    Pastikan stok barang tersedia sebelum update permintaan.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Jumlah <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                value="{{ old('quantity', $backupRequest->quantity) }}"
                                class="form-control @error('quantity') is-invalid @enderror"
                                min="1"
                                {{ !$location ? 'disabled' : '' }}
                            >

                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Jumlah barang yang diminta.
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">
                                Prioritas <span class="text-danger">*</span>
                            </label>

                            <select
                                name="priority"
                                class="form-select @error('priority') is-invalid @enderror"
                                {{ !$location ? 'disabled' : '' }}
                            >
                                <option value="Rendah" {{ old('priority', $backupRequest->priority) === 'Rendah' ? 'selected' : '' }}>
                                    Rendah
                                </option>
                                <option value="Sedang" {{ old('priority', $backupRequest->priority) === 'Sedang' ? 'selected' : '' }}>
                                    Sedang
                                </option>
                                <option value="Tinggi" {{ old('priority', $backupRequest->priority) === 'Tinggi' ? 'selected' : '' }}>
                                    Tinggi
                                </option>
                                <option value="Darurat" {{ old('priority', $backupRequest->priority) === 'Darurat' ? 'selected' : '' }}>
                                    Darurat
                                </option>
                            </select>

                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Tentukan urgensi kebutuhan barang backup.
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Alasan Permintaan <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="reason"
                                rows="6"
                                class="form-control @error('reason') is-invalid @enderror"
                                placeholder="Jelaskan alasan kebutuhan barang backup, kondisi di lapangan, dan dampaknya terhadap operasional parkir..."
                                {{ !$location ? 'disabled' : '' }}
                            >{{ old('reason', $backupRequest->reason) }}</textarea>

                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="help-text">
                                Jelaskan alasan perubahan permintaan dengan jelas.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Informasi Permintaan</h5>

                    <table class="table table-borderless align-middle mb-0 data-table">
                        <tr>
                            <th>No. Permintaan</th>
                            <td class="text-end">{{ $backupRequest->request_number ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td class="text-end">
                                <span class="badge rounded-pill {{ $statusBadgeClass }}">
                                    {{ $backupRequest->status ?? '-' }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Lokasi</th>
                            <td class="text-end">{{ $locationLabel }}</td>
                        </tr>

                        <tr>
                            <th>Dibuat Pada</th>
                            <td class="text-end">{{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Diperbarui</th>
                            <td class="text-end">{{ $backupRequest->updated_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Ringkasan Saat Ini</h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="summary-box">
                                <div class="summary-label">Barang</div>
                                <div class="summary-value">
                                    {{ $backupRequest->backupItem->item_name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="summary-box">
                                <div class="summary-label">Jumlah</div>
                                <div class="summary-value text-primary">
                                    {{ number_format($backupRequest->quantity ?? 0) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="summary-box">
                                <div class="summary-label">Prioritas</div>
                                <div class="summary-value text-warning">
                                    {{ $backupRequest->priority ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Catatan Edit</h5>

                    <div class="alert alert-warning rounded-4 border-0 mb-0">
                        Permintaan yang sudah disetujui, ditolak, diproses, atau selesai tidak dapat diubah lagi.
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
                                Walaupun history terlihat untuk satu lokasi, edit hanya boleh dilakukan oleh pembuat permintaan.
                            </div>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon warning">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Status Menunggu</div>
                            <div class="text-muted small">
                                Data hanya bisa diubah selama status masih Menunggu Verifikasi.
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
                    <h6 class="fw-bold mb-1 text-dark">Update Permintaan Backup</h6>
                    <p class="text-muted small mb-0">
                        Simpan perubahan jika seluruh data sudah sesuai.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4" {{ !$location ? 'disabled' : '' }}>
                        <i class="bi bi-save me-1"></i>
                        Update Permintaan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection