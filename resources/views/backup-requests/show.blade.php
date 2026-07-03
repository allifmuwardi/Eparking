@extends('layouts.app')

@section('title', 'Detail Permintaan Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Permintaan Backup')
@section('page_subtitle', 'Detail pengajuan, verifikasi, proses, dan penyelesaian backup barang')

@section('content')
@php
    $authUser = Auth::user();
    $currentRole = $authUser->role ?? '';

    $isPetugas = in_array($currentRole, ['petugas', 'petugas_parkir'], true);
    $isManager = in_array($currentRole, ['manajer', 'manager', 'manajer_operasional'], true);
    $isAdminOperational = in_array($currentRole, ['admin', 'admin_operasional'], true);
    $isOwnerPetugas = $isPetugas && (int) $authUser->id === (int) $backupRequest->user_id;

    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Dalam Proses' => 'bg-info text-dark',
            'Selesai' => 'bg-primary',
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

    $statusIcon = match ($backupRequest->status ?? '') {
        'Menunggu Verifikasi' => 'bi-hourglass-split',
        'Disetujui' => 'bi-check2-square',
        'Ditolak' => 'bi-x-circle',
        'Dalam Proses' => 'bi-arrow-repeat',
        'Selesai' => 'bi-check-circle',
        default => 'bi-box-seam',
    };

    $requesterName = $backupRequest->requester->full_name ?? $backupRequest->requester->name ?? '-';
    $verifierName = $backupRequest->verifier->full_name ?? $backupRequest->verifier->name ?? '-';
    $processorName = $backupRequest->processor->full_name ?? $backupRequest->processor->name ?? '-';

    $canPetugasModify = $isOwnerPetugas && ($backupRequest->status ?? '') === 'Menunggu Verifikasi';
    $canManagerApproveReject = $isManager && ($backupRequest->status ?? '') === 'Menunggu Verifikasi';
    $canAdminProcess = $isAdminOperational && ($backupRequest->status ?? '') === 'Disetujui';
    $canAdminComplete = $isAdminOperational && ($backupRequest->status ?? '') === 'Dalam Proses';

    $step2Active = in_array($backupRequest->status, ['Disetujui', 'Dalam Proses', 'Selesai'], true);
    $step3Active = in_array($backupRequest->status, ['Dalam Proses', 'Selesai'], true);
    $step4Active = ($backupRequest->status ?? '') === 'Selesai';
    $isRejected = ($backupRequest->status ?? '') === 'Ditolak';
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
        color: #fff;
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
        color: #fff;
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
        color: #fff;
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
        background: #fff;
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
        background: #fff;
        padding: 16px;
        height: 100%;
    }

    .progress-step.active {
        background: #f0f7ff;
        border-color: #9fc5f8;
    }

    .progress-step.done {
        background: #e7f7ee;
        border-color: #a9e3c2;
    }

    .progress-step.rejected {
        background: #fde8e8;
        border-color: #f2a9ad;
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
        word-break: break-word;
    }

    .info-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .reason-box {
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

    .action-panel {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #fff;
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

    textarea.form-control {
        min-height: 110px;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3 class="detail-title">Detail Permintaan Backup Barang</h3>
                <p class="detail-subtitle">
                    {{ $backupRequest->request_number ?? '-' }} — {{ $backupRequest->backupItem->item_name ?? 'Barang Backup' }}
                </p>
            </div>
        </div>

        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3">
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
                            <div class="status-label">Status Permintaan</div>
                            <div class="status-value">{{ $backupRequest->status ?? '-' }}</div>
                            <p class="status-help">
                                Permintaan dibuat oleh <strong>{{ $requesterName }}</strong>
                                pada <strong>{{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }} WIB</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill {{ $priorityBadgeClass($backupRequest->priority ?? '') }}">
                            Prioritas: {{ $backupRequest->priority ?? '-' }}
                        </span>

                        <span class="badge rounded-pill {{ $statusBadgeClass($backupRequest->status ?? '') }}">
                            {{ $backupRequest->status ?? '-' }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            {{ number_format($backupRequest->quantity ?? 0) }} {{ $backupRequest->backupItem->unit ?? 'unit' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="mb-3">
            <h5 class="section-title-local">Progress Permintaan Backup</h5>
            <p class="section-subtitle-local">
                Posisi permintaan berdasarkan alur backup barang operasional.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md">
                <div class="progress-step active">
                    <div class="progress-icon"><i class="bi bi-send-check"></i></div>
                    <div class="fw-bold mb-1">1. Pengajuan</div>
                    <div class="text-muted small fw-semibold">Petugas membuat permintaan.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step2Active ? 'active' : '' }} {{ $isRejected ? 'rejected' : '' }}">
                    <div class="progress-icon"><i class="bi {{ $isRejected ? 'bi-x-circle' : 'bi-person-check' }}"></i></div>
                    <div class="fw-bold mb-1">{{ $isRejected ? '2. Ditolak' : '2. Verifikasi' }}</div>
                    <div class="text-muted small fw-semibold">Manajer menyetujui atau menolak.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step3Active ? 'active' : '' }}">
                    <div class="progress-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div class="fw-bold mb-1">3. Proses Admin</div>
                    <div class="text-muted small fw-semibold">Admin menyiapkan barang.</div>
                </div>
            </div>

            <div class="col-md">
                <div class="progress-step {{ $step4Active ? 'done' : '' }}">
                    <div class="progress-icon"><i class="bi bi-check-circle"></i></div>
                    <div class="fw-bold mb-1">4. Selesai</div>
                    <div class="text-muted small fw-semibold">Barang diserahkan dan stok berkurang.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Permintaan</h5>
                    <p class="section-subtitle-local">
                        Detail barang backup yang diajukan oleh Petugas Parkir.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nomor Request</div>
                            <div class="info-value">{{ $backupRequest->request_number ?? '-' }}</div>
                            <div class="info-help">Nomor unik permintaan backup</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Tanggal Pengajuan</div>
                            <div class="info-value">{{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                            <div class="info-help">Waktu permintaan dibuat</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Barang Backup</div>
                            <div class="info-value">{{ $backupRequest->backupItem->item_name ?? '-' }}</div>
                            <div class="info-help">Kode: {{ $backupRequest->backupItem->item_code ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Jumlah</div>
                            <div class="info-value">
                                {{ number_format($backupRequest->quantity ?? 0) }} {{ $backupRequest->backupItem->unit ?? 'unit' }}
                            </div>
                            <div class="info-help">
                                Stok saat ini: {{ number_format($backupRequest->backupItem->stock ?? 0) }} {{ $backupRequest->backupItem->unit ?? 'unit' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Lokasi Parkir</div>
                            <div class="info-value">{{ $backupRequest->parkingLocation->location_name ?? '-' }}</div>
                            <div class="info-help">Kode: {{ $backupRequest->parkingLocation->location_code ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Pemohon</div>
                            <div class="info-value">{{ $requesterName }}</div>
                            <div class="info-help">NIK: {{ $backupRequest->requester->username ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Prioritas</div>
                            <span class="badge rounded-pill {{ $priorityBadgeClass($backupRequest->priority ?? '') }}">
                                {{ $backupRequest->priority ?? '-' }}
                            </span>
                            <div class="info-help mt-2">Tingkat urgensi kebutuhan barang.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Status</div>
                            <span class="badge rounded-pill {{ $statusBadgeClass($backupRequest->status ?? '') }}">
                                {{ $backupRequest->status ?? '-' }}
                            </span>
                            <div class="info-help mt-2">Status proses permintaan.</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-label mt-2">Alasan Kebutuhan</div>
                        <div class="reason-box">
                            {{ $backupRequest->reason ?? '-' }}
                        </div>
                    </div>

                    @if ($backupRequest->verification_note)
                        <div class="col-md-12">
                            <div class="alert alert-primary mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Catatan Verifikasi
                                </div>
                                {{ $backupRequest->verification_note }}
                            </div>
                        </div>
                    @endif

                    @if ($backupRequest->rejection_reason)
                        <div class="col-md-12">
                            <div class="alert alert-danger mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Alasan Penolakan
                                </div>
                                {{ $backupRequest->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if (!empty($backupRequest->handover_photo))
                <div class="page-card p-4">
                    <div class="mb-4">
                        <h5 class="section-title-local">Bukti Serah Terima</h5>
                        <p class="section-subtitle-local">
                            Dokumentasi penyerahan barang backup oleh Admin Operasional.
                        </p>
                    </div>

                    <a href="{{ asset('storage/' . $backupRequest->handover_photo) }}" target="_blank">
                        <img src="{{ asset('storage/' . $backupRequest->handover_photo) }}" class="img-fluid rounded-4 border" alt="Bukti Serah Terima">
                    </a>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="action-panel mb-4 sticky-top" style="top: 120px;">
                <h5 class="section-title-local">Informasi Proses</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan verifikasi dan proses admin.
                </p>

                <div class="side-row">
                    <div class="side-label">Status</div>
                    <div class="side-value">
                        <span class="badge rounded-pill {{ $statusBadgeClass($backupRequest->status ?? '') }}">
                            {{ $backupRequest->status ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diverifikasi Oleh</div>
                    <div class="side-value">{{ $verifierName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diproses Oleh</div>
                    <div class="side-value">{{ $processorName }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Dibuat</div>
                    <div class="side-value">{{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diperbarui</div>
                    <div class="side-value">{{ $backupRequest->updated_at?->format('d M Y H:i') ?? '-' }} WIB</div>
                </div>

                <hr>

                @if ($backupRequest->status === 'Menunggu Verifikasi')
                    <div class="alert alert-warning mb-0">
                        <div class="fw-bold mb-1">Menunggu Verifikasi</div>
                        Permintaan sedang menunggu keputusan Manajer Operasional.
                    </div>
                @elseif ($backupRequest->status === 'Disetujui')
                    <div class="alert alert-success mb-0">
                        <div class="fw-bold mb-1">Disetujui</div>
                        Permintaan sudah disetujui dan menunggu diproses Admin Operasional.
                    </div>
                @elseif ($backupRequest->status === 'Dalam Proses')
                    <div class="alert alert-info mb-0">
                        <div class="fw-bold mb-1">Dalam Proses</div>
                        Admin Operasional sedang menyiapkan barang backup.
                    </div>
                @elseif ($backupRequest->status === 'Selesai')
                    <div class="alert alert-primary mb-0">
                        <div class="fw-bold mb-1">Selesai</div>
                        Barang backup sudah diserahkan dan permintaan selesai.
                    </div>
                @elseif ($backupRequest->status === 'Ditolak')
                    <div class="alert alert-danger mb-0">
                        <div class="fw-bold mb-1">Ditolak</div>
                        Permintaan tidak dapat dilanjutkan. Lihat alasan penolakan.
                    </div>
                @endif
            </div>

            {{-- AKSI KHUSUS MANAJER: approve / reject. Manajer tidak boleh melihat tombol hapus. --}}
            @if ($canManagerApproveReject)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Verifikasi Manajer</h5>
                    <p class="section-subtitle-local mb-3">
                        Setujui atau tolak permintaan backup barang dari Petugas Parkir.
                    </p>

                    <form method="POST" action="{{ route('backup-requests.approve', $backupRequest) }}" class="mb-3">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Catatan Verifikasi</label>
                            <textarea
                                name="verification_note"
                                class="form-control"
                                rows="4"
                                placeholder="Catatan persetujuan jika diperlukan..."
                            >{{ old('verification_note') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Setujui Permintaan
                        </button>
                    </form>

                    <hr>

                    <form
                        method="POST"
                        action="{{ route('backup-requests.reject', $backupRequest) }}"
                        onsubmit="return confirm('Yakin ingin menolak permintaan backup ini?')"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="rejection_reason"
                                class="form-control @error('rejection_reason') is-invalid @enderror"
                                rows="4"
                                placeholder="Masukkan alasan penolakan..."
                                required
                            >{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger w-100 rounded-3">
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak Permintaan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canAdminProcess)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Proses Admin</h5>
                    <p class="section-subtitle-local mb-3">
                        Ubah status menjadi Dalam Proses saat barang mulai disiapkan.
                    </p>

                    <form method="POST" action="{{ route('backup-requests.process', $backupRequest) }}" onsubmit="return confirm('Proses permintaan backup ini?')">
                        @csrf

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Proses Permintaan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canAdminComplete)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Selesaikan Permintaan</h5>
                    <p class="section-subtitle-local mb-3">
                        Selesaikan permintaan jika barang sudah diserahkan. Stok barang akan berkurang sesuai jumlah permintaan.
                    </p>

                    <form method="POST" action="{{ route('backup-requests.complete', $backupRequest) }}" enctype="multipart/form-data" onsubmit="return confirm('Yakin permintaan sudah selesai dan barang sudah diserahkan?')">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Bukti Serah Terima</label>
                            <input type="file" name="handover_photo" class="form-control @error('handover_photo') is-invalid @enderror" accept="image/png,image/jpeg,image/jpg">
                            <div class="form-text fw-semibold">Opsional. Upload foto bukti penyerahan barang jika tersedia.</div>
                            @error('handover_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Selesaikan Permintaan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canPetugasModify)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title-local">Aksi Petugas</h5>
                    <p class="section-subtitle-local mb-3">
                        Permintaan masih dapat diedit atau dihapus selama belum diverifikasi.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('backup-requests.edit', $backupRequest) }}" class="btn btn-warning text-white rounded-3">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Permintaan
                        </a>

                        <form method="POST" action="{{ route('backup-requests.destroy', $backupRequest) }}" onsubmit="return confirm('Yakin ingin menghapus permintaan backup ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger w-100 rounded-3">
                                <i class="bi bi-trash me-1"></i>
                                Hapus Permintaan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="d-grid">
                <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Daftar Backup
                </a>
            </div>
        </div>
    </div>
</div>
@endsection