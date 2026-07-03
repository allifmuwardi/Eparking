@extends('layouts.app')

@section('title', 'Detail Laporan Kendala | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Laporan Kendala')
@section('page_subtitle', 'Detail informasi, status, histori, dan follow up laporan kendala')

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

    $statusIcon = match ($issueReport->status ?? '') {
        'Menunggu Verifikasi' => 'bi-hourglass-split',
        'Dalam Proses' => 'bi-tools',
        'Menunggu Informasi' => 'bi-info-circle',
        'Selesai Ditangani' => 'bi-check-circle',
        'Ditolak' => 'bi-x-circle',
        'Ditutup / Diarsipkan' => 'bi-archive',
        default => 'bi-clipboard',
    };

    $locationName = $issueReport->parkingLocation->location_name ?? '-';
    $locationCode = $issueReport->parkingLocation->location_code ?? '-';
@endphp

<style>
    .detail-header-icon {
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

    .side-panel {
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

    .timeline-card {
        flex: 1;
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
    }

    .follow-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 16px;
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

    @media (max-width: 768px) {
        .detail-title {
            font-size: 22px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="detail-header-icon">
                <i class="bi bi-clipboard2-pulse"></i>
            </div>

            <div>
                <h3 class="detail-title">Detail Laporan Kendala</h3>
                <p class="detail-subtitle">
                    {{ $issueReport->report_number ?? '-' }} — {{ $issueReport->title ?? '-' }}
                </p>
            </div>
        </div>

        <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3">
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
                                Laporan ini dibuat oleh
                                <strong>{{ $issueReport->reporter->full_name ?? $issueReport->reporter->name ?? '-' }}</strong>
                                pada
                                <strong>{{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }} WIB</strong>.
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Kendala</h5>
                    <p class="section-subtitle-local">
                        Informasi utama laporan kendala yang dikirim oleh Petugas Parkir.
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
                            <div class="info-label">Lokasi Parkir</div>
                            <div class="info-value">{{ $locationName }}</div>
                            <div class="info-help">Kode: {{ $locationCode }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Area / Kota</div>
                            <div class="info-value">{{ $issueReport->parkingLocation->area ?? '-' }}</div>
                            <div class="info-help">{{ $issueReport->parkingLocation->city ?? 'Area operasional parkir' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Kategori Kendala</div>
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
                        <div class="info-box">
                            <div class="info-label">Petugas Pelapor</div>
                            <div class="info-value">
                                {{ $issueReport->reporter->full_name ?? $issueReport->reporter->name ?? '-' }}
                            </div>
                            <div class="info-help">
                                Username/NIK: {{ $issueReport->reporter->username ?? '-' }}
                            </div>
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
                                    Alasan Laporan Ditolak
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
                        Dokumentasi awal yang dilampirkan saat membuat laporan.
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

            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Histori Laporan</h5>
                    <p class="section-subtitle-local">
                        Riwayat perubahan status dan aktivitas pada laporan kendala.
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
                                <div class="mt-2">
                                    {{ $history->notes }}
                                </div>
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
            <div class="side-panel mb-4 sticky-top" style="top: 120px;">
                <h5 class="section-title-local">Informasi Penanganan</h5>
                <p class="section-subtitle-local mb-3">
                    Status verifikasi, teknisi, dan proses penutupan laporan.
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
                    <div class="side-value">
                        {{ $issueReport->assignedTechnician->full_name ?? $issueReport->assignedTechnician->name ?? '-' }}
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diverifikasi Oleh</div>
                    <div class="side-value">
                        {{ $issueReport->verifier->full_name ?? $issueReport->verifier->name ?? '-' }}
                    </div>
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

                @if ($issueReport->status === 'Menunggu Verifikasi')
                    <div class="alert alert-warning mb-0">
                        <div class="fw-bold mb-1">Menunggu Verifikasi</div>
                        Laporan sedang menunggu verifikasi dari Manajer Operasional.
                    </div>
                @elseif ($issueReport->status === 'Dalam Proses')
                    <div class="alert alert-info mb-0">
                        <div class="fw-bold mb-1">Sedang Dalam Proses</div>
                        Laporan sudah diverifikasi dan sedang ditangani oleh Teknisi Vendor.
                    </div>
                @elseif ($issueReport->status === 'Menunggu Informasi')
                    <div class="alert alert-primary mb-0">
                        <div class="fw-bold mb-1">Menunggu Informasi</div>
                        Teknisi membutuhkan informasi tambahan sebelum melanjutkan penanganan.
                    </div>
                @elseif ($issueReport->status === 'Selesai Ditangani')
                    <div class="alert alert-success mb-0">
                        <div class="fw-bold mb-1">Selesai Ditangani</div>
                        Kendala sudah selesai ditangani dan menunggu penutupan oleh Manajer.
                    </div>
                @elseif ($issueReport->status === 'Ditutup / Diarsipkan')
                    <div class="alert alert-secondary mb-0">
                        <div class="fw-bold mb-1">Laporan Ditutup</div>
                        Laporan sudah ditutup dan diarsipkan oleh Manajer Operasional.
                    </div>
                @elseif ($issueReport->status === 'Ditolak')
                    <div class="alert alert-danger mb-0">
                        <div class="fw-bold mb-1">Laporan Ditolak</div>
                        Laporan tidak dapat diproses. Lihat alasan penolakan pada detail laporan.
                    </div>
                @endif

                <div class="d-grid mt-3">
                    <a href="{{ route('issue-reports.index') }}" class="btn btn-soft rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <div class="page-card p-4">
                <h5 class="section-title-local">Follow Up Teknisi</h5>
                <p class="section-subtitle-local mb-3">
                    Update status dan catatan penanganan dari Teknisi Vendor.
                </p>

                @forelse ($issueReport->followUps as $followUp)
                    <div class="follow-card">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
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
        </div>
    </div>
</div>
@endsection