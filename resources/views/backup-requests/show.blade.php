@extends('layouts.app')

@section('title', 'Detail Permintaan Backup | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();
    $currentRole = $authUser->role;

    $isPetugas = $currentRole === 'petugas';
    $isManager = $currentRole === 'manajer';
    $isAdminOperational = $currentRole === 'admin';
    $isOwnerPetugas = $isPetugas && $authUser->id === $backupRequest->user_id;

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

    $locationLabel = $backupRequest->parkingLocation->location_name ?? '-';

    if (!empty($backupRequest->parkingLocation->location_code)) {
        $locationLabel .= ' - ' . $backupRequest->parkingLocation->location_code;
    }

    $requesterName = $backupRequest->requester->full_name
        ?? $backupRequest->requester->name
        ?? '-';

    $verifierName = $backupRequest->verifier->full_name
        ?? $backupRequest->verifier->name
        ?? '-';

    $processorName = $backupRequest->processor->full_name
        ?? $backupRequest->processor->name
        ?? '-';

    $canPetugasModify = $isOwnerPetugas && $backupRequest->status === 'Menunggu Verifikasi';
@endphp

<style>
    .detail-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .detail-page-subtitle {
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

    .hero-card {
        border-radius: 22px;
        border: 1px solid #d7e3f7;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 24px;
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.05);
    }

    .hero-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 5px;
    }

    .hero-number {
        color: #0d6efd;
        font-size: 28px;
        font-weight: 950;
        margin-bottom: 8px;
    }

    .hero-item {
        color: #071b4d;
        font-size: 19px;
        font-weight: 950;
        margin-bottom: 12px;
    }

    .info-card {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
        height: 100%;
    }

    .info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 7px;
    }

    .info-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 900;
        margin-bottom: 3px;
        word-break: break-word;
    }

    .info-help {
        color: #8a9abc;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .location-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .location-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .progress-step {
        display: flex;
        gap: 14px;
        position: relative;
        padding-bottom: 20px;
    }

    .progress-step:last-child {
        padding-bottom: 0;
    }

    .progress-dot {
        width: 38px;
        height: 38px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        color: #ffffff;
        background: #adb5bd;
    }

    .progress-dot.active {
        background: linear-gradient(145deg, #1f6de2, #0649bd);
    }

    .progress-dot.success {
        background: #198754;
    }

    .progress-dot.danger {
        background: #dc3545;
    }

    .progress-content {
        flex: 1;
        padding-top: 2px;
    }

    .progress-title {
        color: #071b4d;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .progress-desc {
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

    .form-control {
        min-height: 48px;
        border-radius: 13px;
        border: 1px solid #d7e3f7;
        background-color: #f8fbff;
        color: #071b4d;
        font-weight: 650;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    textarea.form-control {
        min-height: 120px;
    }

    .process-table th {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        padding-left: 0;
    }

    .process-table td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 750;
    }

    .note-box {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 16px;
    }

    .handover-photo {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 12px;
    }

    .handover-photo img {
        border-radius: 14px;
        border: 1px solid #d7e3f7;
        max-height: 320px;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3 class="detail-page-title">Detail Permintaan Barang Backup</h3>
                <p class="detail-page-subtitle">
                    Pantau detail pengajuan, approval Manajer, dan proses Admin Operasional.
                </p>
            </div>
        </div>

        <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Hero --}}
    <div class="hero-card mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="hero-label">Nomor Permintaan</div>

                <div class="hero-number">
                    {{ $backupRequest->request_number ?? '-' }}
                </div>

                <div class="hero-item">
                    {{ $backupRequest->backupItem->item_name ?? 'Barang Backup' }}
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $statusBadgeClass($backupRequest->status ?? '') }}">
                        {{ $backupRequest->status ?? '-' }}
                    </span>

                    <span class="badge rounded-pill {{ $priorityBadgeClass($backupRequest->priority ?? '') }}">
                        Prioritas: {{ $backupRequest->priority ?? '-' }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        Jumlah: {{ number_format($backupRequest->quantity ?? 0) }} {{ $backupRequest->backupItem->unit ?? '' }}
                    </span>

                    @if ($isOwnerPetugas)
                        <span class="badge rounded-pill bg-primary">
                            Request Anda
                        </span>
                    @elseif ($isPetugas)
                        <span class="badge rounded-pill bg-info text-dark">
                            History Lokasi
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-end">
                <div class="hero-label">Tanggal Pengajuan</div>
                <div class="fw-bold text-dark">
                    {{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="hero-label mt-3">Pemohon</div>
                <div class="fw-bold text-dark">
                    {{ $requesterName }}
                </div>
                <div class="text-muted small">
                    NIK: {{ $backupRequest->requester->username ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    @if ($isPetugas && !$isOwnerPetugas)
        <div class="alert alert-primary rounded-4 border-0 mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                History Lokasi Operasional
            </div>
            Permintaan ini dibuat oleh Petugas lain di lokasi operasional yang sama. Anda dapat melihat detailnya sebagai history lokasi, tetapi tidak dapat mengubah atau menghapus request ini.
        </div>
    @endif

    <div class="row g-4">
        {{-- Konten Kiri --}}
        <div class="col-lg-8">
            {{-- Lokasi Operasional --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title">Lokasi Operasional</h5>
                    <p class="section-subtitle">
                        Permintaan backup tercatat pada lokasi/cabang operasional berikut.
                    </p>
                </div>

                <div class="location-card">
                    <div class="d-flex align-items-start gap-3">
                        <div class="location-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <div class="info-label">Lokasi / Cabang</div>
                            <div class="fs-5 fw-bold text-primary mb-1">
                                {{ $locationLabel }}
                            </div>
                            <div class="text-muted small">
                                Kode Lokasi: {{ $backupRequest->parkingLocation->location_code ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Permintaan --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="section-title">Informasi Permintaan</h5>
                    <p class="section-subtitle">
                        Detail barang, stok, jumlah, prioritas, dan alasan kebutuhan barang backup.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Pembuat Request</div>
                            <div class="info-value">{{ $requesterName }}</div>
                            <p class="info-help">
                                NIK: {{ $backupRequest->requester->username ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Role Pemohon</div>
                            <div class="info-value">
                                Petugas Parkir
                            </div>
                            <p class="info-help">
                                Pengajuan dibuat melalui akun Petugas.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Barang Backup</div>
                            <div class="info-value">
                                {{ $backupRequest->backupItem->item_name ?? '-' }}
                            </div>
                            <p class="info-help">
                                Kode: {{ $backupRequest->backupItem->item_code ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Stok Saat Ini</div>
                            <div class="info-value">
                                {{ number_format($backupRequest->backupItem->stock ?? 0) }}
                                {{ $backupRequest->backupItem->unit ?? '' }}
                            </div>
                            <p class="info-help">
                                Stok master barang backup saat ini.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Jumlah Diminta</div>
                            <div class="info-value">
                                {{ number_format($backupRequest->quantity ?? 0) }}
                                {{ $backupRequest->backupItem->unit ?? '' }}
                            </div>
                            <p class="info-help">
                                Jumlah barang yang diajukan.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Prioritas</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill {{ $priorityBadgeClass($backupRequest->priority ?? '') }}">
                                    {{ $backupRequest->priority ?? '-' }}
                                </span>
                            </div>
                            <p class="info-help mt-2">
                                Tingkat urgensi kebutuhan operasional.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-card">
                            <div class="info-label">Alasan Permintaan</div>
                            <div class="info-value" style="white-space: pre-line;">
                                {{ $backupRequest->reason ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title">Progress Permintaan</h5>
                    <p class="section-subtitle">
                        Alur status dari pengajuan Petugas, approval Manajer, proses Admin, sampai selesai.
                    </p>
                </div>

                <div class="progress-step">
                    <div class="progress-dot success">
                        <i class="bi bi-send-check"></i>
                    </div>
                    <div class="progress-content">
                        <div class="progress-title">Diajukan Petugas</div>
                        <p class="progress-desc">
                            Permintaan dibuat oleh {{ $requesterName }} pada
                            {{ $backupRequest->created_at?->format('d M Y H:i') ?? '-' }}.
                        </p>
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-dot
                        @if ($backupRequest->status === 'Ditolak') danger
                        @elseif (in_array($backupRequest->status, ['Disetujui', 'Dalam Proses', 'Selesai'])) success
                        @elseif ($backupRequest->status === 'Menunggu Verifikasi') active
                        @endif
                    ">
                        @if ($backupRequest->status === 'Ditolak')
                            <i class="bi bi-x-circle"></i>
                        @else
                            <i class="bi bi-person-check"></i>
                        @endif
                    </div>
                    <div class="progress-content">
                        <div class="progress-title">Approval Manajer Operasional</div>
                        <p class="progress-desc">
                            @if ($backupRequest->status === 'Menunggu Verifikasi')
                                Menunggu persetujuan atau penolakan dari Manajer Operasional.
                            @elseif ($backupRequest->status === 'Ditolak')
                                Ditolak oleh {{ $verifierName }} pada {{ $backupRequest->verified_at?->format('d M Y H:i') ?? '-' }}.
                            @elseif (in_array($backupRequest->status, ['Disetujui', 'Dalam Proses', 'Selesai']))
                                Disetujui oleh {{ $verifierName }} pada {{ $backupRequest->verified_at?->format('d M Y H:i') ?? '-' }}.
                            @else
                                Status approval belum tersedia.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-dot
                        @if ($backupRequest->status === 'Dalam Proses') active
                        @elseif ($backupRequest->status === 'Selesai') success
                        @endif
                    ">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="progress-content">
                        <div class="progress-title">Diproses Admin Operasional</div>
                        <p class="progress-desc">
                            @if ($backupRequest->status === 'Disetujui')
                                Menunggu Admin Operasional memproses persiapan barang.
                            @elseif (in_array($backupRequest->status, ['Dalam Proses', 'Selesai']))
                                Diproses oleh {{ $processorName }} sejak {{ $backupRequest->processed_at?->format('d M Y H:i') ?? '-' }}.
                            @else
                                Tahap ini berjalan setelah permintaan disetujui Manajer.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-dot {{ $backupRequest->status === 'Selesai' ? 'success' : '' }}">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div class="progress-content">
                        <div class="progress-title">Selesai dan Stok Diperbarui</div>
                        <p class="progress-desc">
                            @if ($backupRequest->status === 'Selesai')
                                Permintaan selesai pada {{ $backupRequest->completed_at?->format('d M Y H:i') ?? '-' }}.
                            @else
                                Tahap selesai dilakukan setelah barang backup diserahkan dan stok dikurangi otomatis.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Kanan --}}
        <div class="col-lg-4">
            {{-- Aksi Manajer --}}
            @if ($isManager && $backupRequest->status === 'Menunggu Verifikasi')
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-1">Setujui Permintaan</h5>
                    <p class="section-subtitle mb-3">
                        Setujui jika kebutuhan barang sudah sesuai.
                    </p>

                    <form method="POST" action="{{ route('backup-requests.approve', $backupRequest) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Catatan Verifikasi</label>
                            <textarea
                                name="verification_note"
                                rows="4"
                                class="form-control"
                                placeholder="Masukkan catatan jika diperlukan..."
                            >{{ old('verification_note') }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Setujui Permintaan
                        </button>
                    </form>
                </div>

                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-1 text-danger">Tolak Permintaan</h5>
                    <p class="section-subtitle mb-3">
                        Tolak jika data tidak valid atau kebutuhan tidak dapat disetujui.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('backup-requests.reject', $backupRequest) }}"
                        onsubmit="return confirm('Yakin ingin menolak permintaan ini?')"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="rejection_reason"
                                rows="4"
                                class="form-control @error('rejection_reason') is-invalid @enderror"
                                placeholder="Masukkan alasan penolakan..."
                            >{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-danger w-100 rounded-3">
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak Permintaan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Aksi Admin --}}
            @if ($isAdminOperational && $backupRequest->status === 'Disetujui')
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-1">Proses Barang Backup</h5>
                    <p class="section-subtitle mb-3">
                        Ubah status menjadi Dalam Proses setelah barang mulai disiapkan.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('backup-requests.process', $backupRequest) }}"
                        onsubmit="return confirm('Proses permintaan barang backup ini?')"
                    >
                        @csrf

                        <button class="btn btn-info text-white w-100 rounded-3">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Proses Permintaan
                        </button>
                    </form>
                </div>
            @endif

            @if ($isAdminOperational && $backupRequest->status === 'Dalam Proses')
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-1">Selesaikan Permintaan</h5>
                    <p class="section-subtitle mb-3">
                        Selesaikan setelah barang backup diserahkan ke lokasi terkait.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('backup-requests.complete', $backupRequest) }}"
                        enctype="multipart/form-data"
                        onsubmit="return confirm('Selesaikan permintaan ini dan kurangi stok barang?')"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Foto Bukti Penyerahan</label>
                            <input
                                type="file"
                                name="handover_photo"
                                class="form-control @error('handover_photo') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/jpg"
                            >

                            <div class="text-muted small mt-2">
                                Format JPG, JPEG, PNG. Maksimal 2 MB.
                            </div>

                            @error('handover_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-check2-circle me-1"></i>
                            Selesaikan Permintaan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Aksi Petugas --}}
            @if ($canPetugasModify)
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-3">Aksi Permintaan</h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('backup-requests.edit', $backupRequest) }}" class="btn btn-warning text-white rounded-3">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Permintaan
                        </a>

                        <form
                            method="POST"
                            action="{{ route('backup-requests.destroy', $backupRequest) }}"
                            onsubmit="return confirm('Yakin ingin menghapus permintaan ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger rounded-3 w-100">
                                <i class="bi bi-trash me-1"></i>
                                Hapus Permintaan
                            </button>
                        </form>
                    </div>

                    <div class="alert alert-warning rounded-4 border-0 mt-3 mb-0">
                        Edit/hapus hanya bisa dilakukan selama status masih <b>Menunggu Verifikasi</b>.
                    </div>
                </div>
            @endif

            {{-- Informasi Role --}}
            @if ($isManager && in_array($backupRequest->status, ['Disetujui', 'Dalam Proses', 'Selesai']))
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-2">Informasi Alur</h5>
                    <div class="alert alert-primary rounded-4 border-0 mb-0">
                        Permintaan ini sudah melewati tahap approval Manajer. Proses penyerahan barang dilakukan oleh Admin Operasional.
                    </div>
                </div>
            @endif

            @if ($isAdminOperational && $backupRequest->status === 'Menunggu Verifikasi')
                <div class="page-card p-4 mb-4">
                    <h5 class="section-title mb-2">Menunggu Approval</h5>
                    <div class="alert alert-warning rounded-4 border-0 mb-0">
                        Permintaan ini masih menunggu persetujuan Manajer Operasional. Admin dapat memproses setelah status menjadi <b>Disetujui</b>.
                    </div>
                </div>
            @endif

            {{-- Informasi Proses --}}
            <div class="page-card p-4">
                <h5 class="section-title mb-3">Informasi Proses</h5>

                <table class="table table-borderless align-middle mb-0 process-table">
                    <tr>
                        <th>Status</th>
                        <td class="text-end">
                            <span class="badge rounded-pill {{ $statusBadgeClass($backupRequest->status ?? '') }}">
                                {{ $backupRequest->status ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Verifikator</th>
                        <td class="text-end">{{ $verifierName }}</td>
                    </tr>

                    <tr>
                        <th>Waktu Verifikasi</th>
                        <td class="text-end">
                            {{ $backupRequest->verified_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Diproses Oleh</th>
                        <td class="text-end">{{ $processorName }}</td>
                    </tr>

                    <tr>
                        <th>Mulai Diproses</th>
                        <td class="text-end">
                            {{ $backupRequest->processed_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Selesai Pada</th>
                        <td class="text-end">
                            {{ $backupRequest->completed_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                </table>

                @if ($backupRequest->verification_note)
                    <div class="note-box mt-3">
                        <div class="fw-bold text-primary mb-1">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Catatan Verifikasi
                        </div>
                        <div style="white-space: pre-line;">
                            {{ $backupRequest->verification_note }}
                        </div>
                    </div>
                @endif

                @if ($backupRequest->rejection_reason)
                    <div class="alert alert-danger rounded-4 border-0 mt-3 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            Alasan Ditolak
                        </div>
                        <div style="white-space: pre-line;">
                            {{ $backupRequest->rejection_reason }}
                        </div>
                    </div>
                @endif

                @if ($backupRequest->handover_photo)
                    <div class="handover-photo mt-3">
                        <div class="text-muted small fw-bold mb-2">Foto Penyerahan Barang</div>
                        <a href="{{ asset('storage/' . $backupRequest->handover_photo) }}" target="_blank">
                            <img
                                src="{{ asset('storage/' . $backupRequest->handover_photo) }}"
                                class="img-fluid"
                                alt="Foto Penyerahan Barang"
                            >
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection