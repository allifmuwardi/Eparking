@extends('layouts.app')

@section('title', 'Update Status Laporan | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Update Status Laporan')
@section('page_subtitle', 'Update progress penanganan kendala oleh Teknisi Vendor')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-primary',
            'Selesai Ditangani' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Ditutup / Diarsipkan' => 'bg-secondary',
            default => 'bg-secondary',
        };
    };

    $priorityBadgeClass = function ($priority) {
        return match ($priority) {
            'Rendah' => 'bg-success',
            'Sedang' => 'bg-primary',
            'Tinggi' => 'bg-warning text-dark',
            'Darurat' => 'bg-danger',
            default => 'bg-secondary',
        };
    };

    $totalFollowUp = $issueReport->followUps->count();

    $reporterName = $issueReport->reporter->full_name ?? $issueReport->reporter->name ?? '-';
    $technicianName = $issueReport->assignedTechnician->full_name ?? $issueReport->assignedTechnician->name ?? '-';
    $verifierName = $issueReport->verifier->full_name ?? $issueReport->verifier->name ?? '-';

    $canUpdate = in_array($issueReport->status, ['Dalam Proses', 'Menunggu Informasi'], true);

    $step3Active = in_array($issueReport->status, ['Dalam Proses', 'Menunggu Informasi', 'Selesai Ditangani', 'Ditutup / Diarsipkan'], true);
    $step4Active = in_array($issueReport->status, ['Selesai Ditangani', 'Ditutup / Diarsipkan'], true);

    $statusIcon = match ($issueReport->status ?? '') {
        'Dalam Proses' => 'bi-tools',
        'Menunggu Informasi' => 'bi-info-circle',
        'Selesai Ditangani' => 'bi-check-circle',
        'Ditutup / Diarsipkan' => 'bi-archive',
        'Ditolak' => 'bi-x-circle',
        default => 'bi-clipboard-check',
    };
@endphp

<style>
    .detail-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .detail-subtitle {
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

    .status-hero {
        border-radius: 24px;
        padding: 24px;
        color: #ffffff;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 36%),
            linear-gradient(135deg, #0b3969 0%, #0649bd 55%, #0d6efd 100%);
        box-shadow: 0 22px 50px rgba(13, 110, 253, 0.20);
        overflow: hidden;
        position: relative;
    }

    .status-hero::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -70px;
        bottom: -90px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .status-hero-content {
        position: relative;
        z-index: 1;
    }

    .status-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .status-label {
        color: rgba(255, 255, 255, 0.74);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .status-value {
        color: #ffffff;
        font-size: 22px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .status-help {
        color: rgba(255, 255, 255, 0.82);
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.6;
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

    .progress-step {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
        height: 100%;
        transition: all .18s ease;
    }

    .progress-step.active {
        background: #f0f7ff;
        border-color: #9fc5f8;
    }

    .progress-step.done {
        background: #e7f7ee;
        border-color: #a9e3c2;
    }

    .progress-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 12px;
    }

    .info-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
        height: 100%;
    }

    .info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .info-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .info-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .description-box,
    .note-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 18px;
        color: #071b4d;
        font-weight: 650;
        line-height: 1.7;
        min-height: 110px;
        white-space: pre-line;
    }

    .photo-box {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .photo-box img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid #d7e3f7;
    }

    .empty-photo {
        min-height: 220px;
        border-radius: 16px;
        background: #f4f8ff;
        border: 1px dashed #b9cbea;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .action-panel {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
    }

    .follow-card,
    .timeline-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
        margin-bottom: 16px;
    }

    .follow-card:last-child,
    .timeline-card:last-child {
        margin-bottom: 0;
    }

    .timeline-item {
        display: flex;
        gap: 14px;
        margin-bottom: 16px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 19px;
    }

    .empty-state {
        padding: 42px 16px;
        text-align: center;
        color: #7b8caf;
        border-radius: 18px;
        border: 1px dashed #b9cbea;
        background: #f8fbff;
    }

    .side-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #edf3fc;
    }

    .side-row:last-child {
        border-bottom: none;
    }

    .side-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 750;
    }

    .side-value {
        color: #071b4d;
        font-size: 13px;
        font-weight: 950;
        text-align: right;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .backup-box {
        border-radius: 18px;
        border: 1px dashed #b9cbea;
        background: #f8fbff;
        padding: 16px;
    }

    @media (max-width: 768px) {
        .detail-title {
            font-size: 22px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-tools"></i>
            </div>

            <div>
                <h3 class="detail-title">Update Status Laporan</h3>
                <p class="detail-subtitle">
                    {{ $issueReport->report_number ?? '-' }} — {{ $issueReport->title ?? 'Laporan Kendala Parkir' }}
                </p>
            </div>
        </div>

        <a href="{{ route('technician-reports.index') }}" class="btn btn-soft rounded-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="status-hero mb-4">
        <div class="status-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="status-icon">
                            <i class="bi {{ $statusIcon }}"></i>
                        </div>

                        <div>
                            <div class="status-label">Status Penanganan</div>
                            <div class="status-value">{{ $issueReport->status ?? '-' }}</div>
                            <p class="status-help">
                                Laporan ini ditugaskan kepada <strong>{{ $technicianName }}</strong>.
                                Total follow up saat ini: <strong>{{ $totalFollowUp }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill {{ $priorityBadgeClass($issueReport->priority ?? '') }}">
                            Prioritas: {{ $issueReport->priority ?? '-' }}
                        </span>

                        <span class="badge rounded-pill {{ $statusBadgeClass($issueReport->status ?? '') }}">
                            {{ $issueReport->status ?? '-' }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            {{ $issueReport->category ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="mb-3">
            <h5 class="section-title-local">Progress Penanganan</h5>
            <p class="section-subtitle-local">
                Posisi laporan berdasarkan alur kerja Teknisi Vendor.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md">
                <div class="progress-step active">
                    <div class="progress-icon">
                        <i class="bi bi-send-check"></i>
                    </div>
                    <div class="fw-bold mb-1">1. Laporan Masuk</div>
                    <div class="text-muted small fw-semibold">Petugas membuat laporan kendala.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step active">
                    <div class="progress-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="fw-bold mb-1">2. Ditugaskan</div>
                    <div class="text-muted small fw-semibold">Manajer menugaskan laporan kepada teknisi.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step3Active ? 'active' : '' }}">
                    <div class="progress-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="fw-bold mb-1">3. Penanganan</div>
                    <div class="text-muted small fw-semibold">Teknisi melakukan follow up penanganan.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step4Active ? 'done' : '' }}">
                    <div class="progress-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="fw-bold mb-1">4. Selesai</div>
                    <div class="text-muted small fw-semibold">Teknisi menyelesaikan kendala.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Laporan</h5>
                    <p class="section-subtitle-local">
                        Detail laporan kendala yang harus ditangani.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Petugas Pelapor</div>
                            <div class="info-value">{{ $reporterName }}</div>
                            <div class="info-help">NIK: {{ $issueReport->reporter->username ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Lokasi Parkir</div>
                            <div class="info-value">{{ $issueReport->parkingLocation->location_name ?? '-' }}</div>
                            <div class="info-help">Kode: {{ $issueReport->parkingLocation->location_code ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Teknisi Ditugaskan</div>
                            <div class="info-value">{{ $technicianName }}</div>
                            <div class="info-help">NIK: {{ $issueReport->assignedTechnician->username ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Diverifikasi Oleh</div>
                            <div class="info-value">{{ $verifierName }}</div>
                            <div class="info-help">{{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-label mt-2">Judul Kendala</div>
                        <div class="description-box" style="min-height:auto;">
                            {{ $issueReport->title ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-label mt-2">Deskripsi Kendala</div>
                        <div class="description-box">
                            {{ $issueReport->description ?? '-' }}
                        </div>
                    </div>

                    @if ($issueReport->verification_note)
                        <div class="col-md-12">
                            <div class="alert alert-primary mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Catatan Verifikasi Manajer
                                </div>
                                {{ $issueReport->verification_note }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Dokumentasi Kendala</h5>
                    <p class="section-subtitle-local">
                        Foto bukti awal dari Petugas Parkir.
                    </p>
                </div>

                <div class="photo-box">
                    @if ($issueReport->photo)
                        <a href="{{ asset('storage/' . $issueReport->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $issueReport->photo) }}" alt="Foto Bukti Kendala">
                        </a>
                    @else
                        <div class="empty-photo">
                            <div>
                                <i class="bi bi-image text-primary fs-1"></i>
                                <div class="fw-bold mt-2">Tidak ada foto bukti</div>
                                <div class="small text-muted fw-semibold">Petugas tidak mengunggah foto pada laporan ini.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Riwayat Follow Up</h5>
                    <p class="section-subtitle-local">
                        Catatan update penanganan yang sudah diinput oleh Teknisi Vendor.
                    </p>
                </div>

                @forelse ($issueReport->followUps as $followUp)
                    <div class="follow-card">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div>
                                <div class="fw-bold">
                                    {{ $followUp->technician->full_name ?? $followUp->technician->name ?? '-' }}
                                </div>
                                <div class="text-muted small fw-semibold">
                                    {{ $followUp->created_at?->format('d M Y H:i') ?? '-' }} WIB
                                </div>
                            </div>

                            <span class="badge rounded-pill {{ $statusBadgeClass($followUp->new_status ?? '') }}">
                                {{ $followUp->new_status ?? '-' }}
                            </span>
                        </div>

                        <div class="small text-muted fw-semibold mb-2">
                            Status:
                            <span>{{ $followUp->previous_status ?? '-' }}</span>
                            →
                            <span class="text-primary">{{ $followUp->new_status ?? '-' }}</span>
                        </div>

                        <div class="note-box mb-3">
                            {{ $followUp->follow_up_note ?? '-' }}
                        </div>

                        @if ($followUp->need_backup_item)
                            <div class="alert alert-warning py-2 mb-3">
                                <div class="fw-bold">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Membutuhkan Barang Backup
                                </div>
                                <div class="small">
                                    Barang: {{ $followUp->backupItem->item_name ?? '-' }}<br>
                                    Jumlah: {{ $followUp->backup_item_quantity ?? 0 }}<br>
                                    Catatan: {{ $followUp->backup_item_note ?? '-' }}
                                </div>
                            </div>
                        @endif

                        @if ($followUp->documentation_photo)
                            <a href="{{ asset('storage/' . $followUp->documentation_photo) }}" target="_blank">
                                <img
                                    src="{{ asset('storage/' . $followUp->documentation_photo) }}"
                                    class="img-fluid rounded-3 border"
                                    alt="Dokumentasi Follow Up"
                                >
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada follow up penanganan.
                    </div>
                @endforelse
            </div>

            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Histori Laporan</h5>
                    <p class="section-subtitle-local">
                        Riwayat perubahan status dan aktivitas laporan.
                    </p>
                </div>

                @forelse ($issueReport->histories as $history)
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div class="timeline-card">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <div class="fw-bold">
                                        {{ ucwords(str_replace('_', ' ', $history->action ?? 'Aktivitas')) }}
                                    </div>
                                    <div class="text-muted small fw-semibold">
                                        {{ $history->created_at?->format('d M Y H:i') ?? '-' }} WIB
                                        —
                                        {{ $history->user->full_name ?? $history->user->name ?? 'Sistem' }}
                                    </div>
                                </div>

                                @if ($history->new_status)
                                    <span class="badge rounded-pill {{ $statusBadgeClass($history->new_status) }}">
                                        {{ $history->new_status }}
                                    </span>
                                @endif
                            </div>

                            @if ($history->notes)
                                <div class="mt-2">{{ $history->notes }}</div>
                            @endif

                            @if ($history->new_status)
                                <div class="mt-2 small text-muted fw-semibold">
                                    Status:
                                    <span>{{ $history->previous_status ?? '-' }}</span>
                                    →
                                    <span class="text-primary">{{ $history->new_status }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada histori laporan.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="action-panel mb-4 sticky-top" style="top: 120px;">
                <h5 class="section-title-local">Update Status Penanganan</h5>
                <p class="section-subtitle-local mb-3">
                    Input hasil penanganan dan update status laporan.
                </p>

                @if ($canUpdate)
                    <form method="POST"
                          action="{{ route('technician-reports.update-status', $issueReport) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                            <select name="new_status" class="form-select @error('new_status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="Dalam Proses" {{ old('new_status', $issueReport->status) === 'Dalam Proses' ? 'selected' : '' }}>
                                    Dalam Proses
                                </option>
                                <option value="Menunggu Informasi" {{ old('new_status') === 'Menunggu Informasi' ? 'selected' : '' }}>
                                    Menunggu Informasi
                                </option>
                                <option value="Selesai Ditangani" {{ old('new_status') === 'Selesai Ditangani' ? 'selected' : '' }}>
                                    Selesai Ditangani
                                </option>
                            </select>
                            @error('new_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="small text-muted fw-semibold mt-2">
                                Pilih <b>Selesai Ditangani</b> jika kendala sudah selesai dan siap ditutup oleh Manajer.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Penanganan <span class="text-danger">*</span></label>
                            <textarea
                                name="follow_up_note"
                                class="form-control @error('follow_up_note') is-invalid @enderror"
                                placeholder="Contoh: Kendala telah dicek, dilakukan penggantian komponen, sistem kembali normal..."
                                required
                            >{{ old('follow_up_note') }}</textarea>
                            @error('follow_up_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumentasi Penanganan</label>
                            <input
                                type="file"
                                name="documentation_photo"
                                class="form-control @error('documentation_photo') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/jpg"
                            >
                            <div class="form-text">Format JPG, JPEG, PNG. Gunakan foto bukti pekerjaan jika tersedia.</div>
                            @error('documentation_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="backup-box mb-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="need_backup_item"
                                    name="need_backup_item"
                                    {{ old('need_backup_item') ? 'checked' : '' }}
                                >
                                <label class="form-check-label fw-bold" for="need_backup_item">
                                    Membutuhkan Barang Backup
                                </label>
                            </div>

                            <div class="text-muted small fw-semibold mt-1">
                                Centang jika penanganan membutuhkan barang backup dari stok operasional.
                            </div>

                            <div id="backupItemSection" class="mt-3" style="{{ old('need_backup_item') ? '' : 'display:none;' }}">
                                <div class="mb-3">
                                    <label class="form-label">Barang Backup</label>
                                    <select name="backup_item_id" class="form-select @error('backup_item_id') is-invalid @enderror">
                                        <option value="">Pilih Barang Backup</option>
                                        @foreach ($backupItems as $item)
                                            <option value="{{ $item->id }}" {{ old('backup_item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->item_name }} — Stok: {{ $item->stock }} {{ $item->unit ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('backup_item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jumlah</label>
                                    <input
                                        type="number"
                                        name="backup_item_quantity"
                                        value="{{ old('backup_item_quantity') }}"
                                        min="1"
                                        class="form-control @error('backup_item_quantity') is-invalid @enderror"
                                        placeholder="Masukkan jumlah barang"
                                    >
                                    @error('backup_item_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Catatan Barang Backup</label>
                                    <textarea
                                        name="backup_item_note"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Catatan kebutuhan barang backup..."
                                    >{{ old('backup_item_note') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-save me-1"></i>
                            Simpan Update Status
                        </button>
                    </form>
                @else
                    <div class="alert alert-secondary rounded-4 mb-0">
                        Laporan dengan status <b>{{ $issueReport->status }}</b> sudah tidak dapat diperbarui.
                    </div>
                @endif
            </div>

            <div class="page-card p-4">
                <h5 class="section-title-local">Informasi Penugasan</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan data penugasan laporan.
                </p>

                <div class="side-row">
                    <div class="side-label">Petugas</div>
                    <div class="side-value">{{ $reporterName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Teknisi</div>
                    <div class="side-value">{{ $technicianName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Manajer</div>
                    <div class="side-value">{{ $verifierName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Waktu Verifikasi</div>
                    <div class="side-value">{{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Total Follow Up</div>
                    <div class="side-value">{{ $totalFollowUp }}</div>
                </div>

                <div class="d-grid mt-3">
                    <a href="{{ route('technician-reports.index') }}" class="btn btn-soft rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Laporan Ditugaskan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const needBackupItem = document.getElementById('need_backup_item');
    const backupItemSection = document.getElementById('backupItemSection');

    if (needBackupItem && backupItemSection) {
        needBackupItem.addEventListener('change', function () {
            backupItemSection.style.display = this.checked ? '' : 'none';
        });
    }
</script>
@endpush