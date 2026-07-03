@extends('layouts.app')

@section('title', 'Detail Manage Report | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Manage Report')
@section('page_subtitle', 'Verifikasi, assign teknisi, reject, dan closing laporan kendala')

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

    $canVerifyAssign = ($issueReport->status ?? '') === 'Menunggu Verifikasi';
    $canReject = in_array(($issueReport->status ?? ''), ['Menunggu Verifikasi', 'Dalam Proses', 'Menunggu Informasi'], true);
    $canClose = ($issueReport->status ?? '') === 'Selesai Ditangani';

    $reporterName = $issueReport->reporter->full_name ?? $issueReport->reporter->name ?? '-';
    $technicianName = $issueReport->assignedTechnician->full_name ?? $issueReport->assignedTechnician->name ?? 'Belum ditugaskan';
    $verifierName = $issueReport->verifier->full_name ?? $issueReport->verifier->name ?? 'Belum diverifikasi';

    $step2Active = in_array(($issueReport->status ?? ''), ['Dalam Proses', 'Menunggu Informasi', 'Selesai Ditangani', 'Ditutup / Diarsipkan'], true);
    $step3Active = in_array(($issueReport->status ?? ''), ['Dalam Proses', 'Menunggu Informasi', 'Selesai Ditangani', 'Ditutup / Diarsipkan'], true);
    $step4Active = ($issueReport->status ?? '') === 'Ditutup / Diarsipkan';

    $statusIcon = match ($issueReport->status ?? '') {
        'Menunggu Verifikasi' => 'bi-hourglass-split',
        'Dalam Proses' => 'bi-tools',
        'Menunggu Informasi' => 'bi-info-circle',
        'Selesai Ditangani' => 'bi-check-circle',
        'Ditolak' => 'bi-x-circle',
        'Ditutup / Diarsipkan' => 'bi-archive',
        default => 'bi-clipboard',
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
        min-height: 120px;
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

    .timeline-card,
    .follow-card {
        flex: 1;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
    }

    .follow-card {
        margin-bottom: 16px;
    }

    .follow-card:last-child {
        margin-bottom: 0;
    }

    .empty-state {
        padding: 42px 16px;
        text-align: center;
        color: #7b8caf;
        border-radius: 18px;
        border: 1px dashed #b9cbea;
        background: #f8fbff;
    }

    textarea.form-control {
        min-height: 110px;
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
                <i class="bi bi-clipboard-data"></i>
            </div>

            <div>
                <h3 class="detail-title">Detail Manage Report</h3>
                <p class="detail-subtitle">
                    {{ $issueReport->report_number ?? '-' }} — {{ $issueReport->title ?? 'Laporan Kendala Parkir' }}
                </p>
            </div>
        </div>

        <a href="{{ route('manage-reports.index') }}" class="btn btn-soft rounded-3">
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
                            <div class="status-label">Status Laporan</div>
                            <div class="status-value">{{ $issueReport->status ?? '-' }}</div>
                            <p class="status-help">
                                Laporan dibuat oleh <strong>{{ $reporterName }}</strong>
                                pada <strong>{{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }} WIB</strong>.
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
                Posisi laporan berdasarkan alur penanganan kendala parkir.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md">
                <div class="progress-step active">
                    <div class="progress-icon"><i class="bi bi-send-check"></i></div>
                    <div class="fw-bold mb-1">1. Laporan Masuk</div>
                    <div class="text-muted small fw-semibold">Petugas membuat laporan.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step2Active ? 'active' : '' }}">
                    <div class="progress-icon"><i class="bi bi-person-check"></i></div>
                    <div class="fw-bold mb-1">2. Verifikasi</div>
                    <div class="text-muted small fw-semibold">Manajer verifikasi dan assign teknisi.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step3Active ? 'active' : '' }}">
                    <div class="progress-icon"><i class="bi bi-tools"></i></div>
                    <div class="fw-bold mb-1">3. Penanganan</div>
                    <div class="text-muted small fw-semibold">Teknisi melakukan follow up.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step4Active ? 'done' : '' }}">
                    <div class="progress-icon"><i class="bi bi-archive"></i></div>
                    <div class="fw-bold mb-1">4. Closing</div>
                    <div class="text-muted small fw-semibold">Manajer menutup laporan.</div>
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
                        Data utama laporan kendala yang dibuat oleh Petugas Parkir.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nomor Laporan</div>
                            <div class="info-value">{{ $issueReport->report_number ?? '-' }}</div>
                            <div class="info-help">Nomor unik laporan kendala</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Tanggal Laporan</div>
                            <div class="info-value">{{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                            <div class="info-help">Waktu laporan dibuat</div>
                        </div>
                    </div>

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
                            <div class="info-label">Kategori</div>
                            <div class="info-value">{{ $issueReport->category ?? '-' }}</div>
                            <div class="info-help">Jenis kendala yang dilaporkan</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Prioritas</div>
                            <span class="badge rounded-pill {{ $priorityBadgeClass($issueReport->priority ?? '') }}">
                                {{ $issueReport->priority ?? '-' }}
                            </span>
                            <div class="info-help mt-2">Tingkat urgensi laporan</div>
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
                                    Catatan Verifikasi
                                </div>
                                {{ $issueReport->verification_note }}
                            </div>
                        </div>
                    @endif

                    @if ($issueReport->rejection_reason)
                        <div class="col-md-12">
                            <div class="alert alert-danger mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Alasan Penolakan
                                </div>
                                {{ $issueReport->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Foto Bukti Kendala</h5>
                    <p class="section-subtitle-local">
                        Dokumentasi awal yang dilampirkan oleh Petugas Parkir.
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
                    <h5 class="section-title-local">Follow Up Teknisi</h5>
                    <p class="section-subtitle-local">
                        Riwayat update penanganan dari Teknisi Vendor.
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
                                    {{ $followUp->backupItem->item_name ?? '-' }}
                                    sebanyak
                                    {{ $followUp->backup_item_quantity ?? '-' }}
                                    {{ $followUp->backupItem->unit ?? '' }}
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
                        <i class="bi bi-tools fs-1 d-block mb-2"></i>
                        Belum ada follow up teknisi.
                    </div>
                @endforelse
            </div>

            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Histori Laporan</h5>
                    <p class="section-subtitle-local">
                        Riwayat perubahan status dan aktivitas pada laporan ini.
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
                <h5 class="section-title-local">Informasi Penanganan</h5>
                <p class="section-subtitle-local mb-3">
                    Status verifikasi, teknisi, dan closing laporan.
                </p>

                <div class="side-row">
                    <div class="side-label">Status</div>
                    <div class="side-value">
                        <span class="badge rounded-pill {{ $statusBadgeClass($issueReport->status ?? '') }}">
                            {{ $issueReport->status ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Teknisi</div>
                    <div class="side-value">{{ $technicianName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diverifikasi Oleh</div>
                    <div class="side-value">{{ $verifierName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Waktu Verifikasi</div>
                    <div class="side-value">
                        {{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Ditutup Pada</div>
                    <div class="side-value">
                        {{ $issueReport->closed_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>

                <hr>

                @if ($canVerifyAssign)
                    <div class="alert alert-warning mb-0">
                        <div class="fw-bold mb-1">Menunggu Verifikasi</div>
                        Pilih teknisi vendor dan lakukan verifikasi agar laporan masuk ke proses penanganan.
                    </div>
                @elseif ($canClose)
                    <div class="alert alert-success mb-0">
                        <div class="fw-bold mb-1">Siap Ditutup</div>
                        Teknisi sudah menandai laporan selesai ditangani. Laporan dapat ditutup.
                    </div>
                @elseif (($issueReport->status ?? '') === 'Ditutup / Diarsipkan')
                    <div class="alert alert-secondary mb-0">
                        <div class="fw-bold mb-1">Laporan Sudah Ditutup</div>
                        Laporan sudah selesai dan masuk arsip.
                    </div>
                @elseif (($issueReport->status ?? '') === 'Ditolak')
                    <div class="alert alert-danger mb-0">
                        <div class="fw-bold mb-1">Laporan Ditolak</div>
                        Laporan tidak dilanjutkan karena alasan tertentu.
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <div class="fw-bold mb-1">Sedang Diproses</div>
                        Pantau follow up teknisi sampai kendala selesai ditangani.
                    </div>
                @endif
            </div>

            @if ($canVerifyAssign)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Verifikasi & Tugaskan Teknisi</h5>
                    <p class="section-subtitle-local mb-3">
                        Pilih Teknisi Vendor untuk menangani laporan kendala ini.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.verify-assign', $issueReport) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Teknisi Vendor <span class="text-danger">*</span></label>
                            <select name="assigned_technician_id" class="form-select @error('assigned_technician_id') is-invalid @enderror" required>
                                <option value="">Pilih Teknisi Vendor</option>
                                @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ old('assigned_technician_id') == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->full_name ?? $technician->name }} - {{ $technician->username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_technician_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Verifikasi</label>
                            <textarea name="verification_note" class="form-control" placeholder="Masukkan catatan verifikasi jika diperlukan...">{{ old('verification_note') }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Verifikasi & Tugaskan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canReject)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local text-danger">Tolak Laporan</h5>
                    <p class="section-subtitle-local mb-3">
                        Gunakan jika laporan tidak valid atau tidak dapat dilanjutkan.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.reject', $issueReport) }}" onsubmit="return confirm('Yakin ingin menolak laporan ini?')">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Masukkan alasan penolakan..." required>{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-danger w-100 rounded-3">
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak Laporan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canClose)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Tutup Laporan</h5>
                    <p class="section-subtitle-local mb-3">
                        Laporan sudah selesai ditangani oleh Teknisi Vendor. Manajer dapat menutup dan mengarsipkan laporan ini.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.close', $issueReport) }}" onsubmit="return confirm('Yakin ingin menutup laporan ini?')">
                        @csrf

                        <button class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-archive me-1"></i>
                            Tutup / Arsipkan Laporan
                        </button>
                    </form>
                </div>
            @endif

            @if (!$canVerifyAssign && !$canReject && !$canClose)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Aksi Laporan</h5>
                    <p class="section-subtitle-local mb-0">
                        Tidak ada aksi lanjutan untuk status laporan saat ini.
                    </p>
                </div>
            @endif

            <div class="d-grid">
                <a href="{{ route('manage-reports.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Manage Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection