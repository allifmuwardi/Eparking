@extends('layouts.app')

@section('title', 'Detail Barang Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Barang Backup')
@section('page_subtitle', 'Informasi lengkap master barang backup operasional parkir')

@section('content')
@php
    $realStatus = ($backupItem->stock ?? 0) > 0 ? 'Tersedia' : 'Tidak Tersedia';
    $isAvailable = $realStatus === 'Tersedia';
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
        line-height: 1.55;
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
        line-height: 1.5;
        margin-bottom: 0;
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

    .hero-card {
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

    .hero-card::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -80px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 31px;
        flex-shrink: 0;
    }

    .hero-label {
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .hero-title {
        color: #ffffff;
        font-size: 24px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.84);
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
        word-break: break-word;
    }

    .info-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .stock-value {
        font-size: 28px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .description-box {
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

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .avatar-item {
        width: 96px;
        height: 96px;
        border-radius: 28px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        margin: 0 auto 16px;
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

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .hero-title {
            font-size: 21px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3 class="page-title-local">Detail Barang Backup</h3>
                <p class="page-subtitle-local">
                    {{ $backupItem->item_code ?? '-' }} — {{ $backupItem->item_name ?? '-' }}
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('backup-items.edit', $backupItem) }}" class="btn btn-warning text-white rounded-3 px-3">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>

            <a href="{{ route('backup-items.index') }}" class="btn btn-soft rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="hero-card mb-4">
        <div class="hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="hero-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div>
                            <div class="hero-label">Barang Backup</div>
                            <div class="hero-title">
                                {{ $backupItem->item_name ?? '-' }}
                            </div>

                            <p class="hero-subtitle">
                                Kode barang <strong>{{ $backupItem->item_code ?? '-' }}</strong>
                                dengan stok <strong>{{ number_format($backupItem->stock ?? 0) }} {{ $backupItem->unit ?? '' }}</strong>.
                                Barang ini digunakan untuk kebutuhan backup operasional parkir.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill {{ $isAvailable ? 'bg-success' : 'bg-secondary' }}">
                            {{ $realStatus }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            {{ $backupItem->category ?? 'Tanpa Kategori' }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            ID: {{ $backupItem->id }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card p-4">
                <div class="mb-4">
                    <h5 class="section-title-local">Informasi Barang</h5>
                    <p class="section-subtitle-local">
                        Detail data master barang backup dan kondisi ketersediaan stok.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Kode Barang</div>
                            <div class="info-value">{{ $backupItem->item_code ?? '-' }}</div>
                            <div class="info-help">Kode unik barang backup.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nama Barang</div>
                            <div class="info-value">{{ $backupItem->item_name ?? '-' }}</div>
                            <div class="info-help">Nama barang backup operasional.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Kategori</div>
                            <div class="info-value">{{ $backupItem->category ?? '-' }}</div>
                            <div class="info-help">Kategori atau kelompok barang.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Lokasi Penyimpanan</div>
                            <div class="info-value">{{ $backupItem->storage_location ?? '-' }}</div>
                            <div class="info-help">Lokasi fisik penyimpanan barang.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Stok</div>
                            <div class="stock-value {{ $isAvailable ? 'text-success' : 'text-danger' }}">
                                {{ number_format($backupItem->stock ?? 0) }}
                            </div>
                            <div class="info-help">Jumlah stok tersedia.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Satuan</div>
                            <div class="info-value">{{ $backupItem->unit ?? '-' }}</div>
                            <div class="info-help">Satuan barang.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Status</div>
                            <span class="badge rounded-pill {{ $isAvailable ? 'bg-success' : 'bg-secondary' }}">
                                {{ $realStatus }}
                            </span>
                            <div class="info-help mt-2">
                                Status otomatis mengikuti stok.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-label mt-2">Deskripsi</div>
                        <div class="description-box">
                            {{ $backupItem->description ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="side-card mb-4 text-center">
                <div class="avatar-item">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h5 class="fw-bold mb-1">
                    {{ $backupItem->item_code ?? '-' }}
                </h5>

                <p class="text-muted mb-3">
                    {{ $backupItem->item_name ?? '-' }}
                </p>

                <span class="badge rounded-pill {{ $isAvailable ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    {{ $realStatus }}
                </span>
            </div>

            <div class="side-card mb-4">
                <h5 class="section-title-local">Ketersediaan</h5>
                <p class="section-subtitle-local mb-3">
                    Status barang berdasarkan jumlah stok saat ini.
                </p>

                @if ($isAvailable)
                    <div class="alert alert-success rounded-4 border-0 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Barang Tersedia
                        </div>
                        Barang ini dapat dipilih oleh Petugas saat membuat Permintaan Backup.
                    </div>
                @else
                    <div class="alert alert-secondary rounded-4 border-0 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            Barang Tidak Tersedia
                        </div>
                        Barang ini tidak akan muncul di form Permintaan Backup karena stok kosong.
                    </div>
                @endif
            </div>

            <div class="side-card mb-4">
                <h5 class="section-title-local">Informasi Sistem</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan waktu pembuatan dan pembaruan data.
                </p>

                <div class="side-row">
                    <div class="side-label">ID Barang</div>
                    <div class="side-value">{{ $backupItem->id }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Dibuat</div>
                    <div class="side-value">
                        {{ $backupItem->created_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diperbarui</div>
                    <div class="side-value">
                        {{ $backupItem->updated_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>
            </div>

            <div class="side-card">
                <h5 class="section-title-local">Aksi Barang</h5>
                <p class="section-subtitle-local mb-3">
                    Kelola master barang backup.
                </p>

                <div class="d-grid gap-2">
                    <a href="{{ route('backup-items.edit', $backupItem) }}" class="btn btn-warning text-white rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Barang
                    </a>

                    <form
                        method="POST"
                        action="{{ route('backup-items.destroy', $backupItem) }}"
                        onsubmit="return confirm('Yakin ingin menghapus barang backup ini?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger rounded-3 w-100">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Barang
                        </button>
                    </form>

                    <a href="{{ route('backup-items.index') }}" class="btn btn-soft rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Daftar
                    </a>
                </div>

                <div class="alert alert-warning rounded-4 mt-3 mb-0">
                    Jika barang sudah pernah digunakan pada transaksi permintaan backup, lebih aman ubah stok menjadi
                    <b>0</b> daripada menghapus data.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection