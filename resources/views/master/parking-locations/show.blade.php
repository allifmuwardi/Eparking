@extends('layouts.app')

@section('title', 'Detail Lokasi Parkir | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Detail Lokasi Parkir')
@section('page_subtitle', 'Informasi lengkap lokasi operasional parkir')

@section('content')
@php
    $isActive = ($parkingLocation->status ?? '') === 'Aktif';
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

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .avatar-location {
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
                <i class="bi bi-geo-alt"></i>
            </div>

            <div>
                <h3 class="page-title-local">Detail Lokasi Parkir</h3>
                <p class="page-subtitle-local">
                    {{ $parkingLocation->location_code ?? '-' }} — {{ $parkingLocation->location_name ?? '-' }}
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('parking-locations.edit', $parkingLocation) }}" class="btn btn-warning text-white rounded-3 px-3">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>

            <a href="{{ route('parking-locations.index') }}" class="btn btn-soft rounded-3 px-3">
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
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <div class="hero-label">Lokasi Parkir</div>
                            <div class="hero-title">
                                {{ $parkingLocation->location_name ?? '-' }}
                            </div>

                            <p class="hero-subtitle">
                                Kode lokasi <strong>{{ $parkingLocation->location_code ?? '-' }}</strong>
                                dengan area <strong>{{ $parkingLocation->area ?? 'belum diisi' }}</strong>.
                                Data ini digunakan pada proses operasional parkir.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                            {{ $parkingLocation->status ?? '-' }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            {{ $parkingLocation->area ?? 'Area belum diisi' }}
                        </span>

                        <span class="badge rounded-pill bg-light text-dark">
                            ID: {{ $parkingLocation->id }}
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
                    <h5 class="section-title-local">Informasi Lokasi</h5>
                    <p class="section-subtitle-local">
                        Detail data master lokasi parkir yang digunakan pada modul operasional.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Kode Lokasi</div>
                            <div class="info-value">{{ $parkingLocation->location_code ?? '-' }}</div>
                            <div class="info-help">Kode unik lokasi parkir.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nama Lokasi</div>
                            <div class="info-value">{{ $parkingLocation->location_name ?? '-' }}</div>
                            <div class="info-help">Nama lokasi operasional.</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-box">
                            <div class="info-label">Alamat</div>
                            <div class="info-value" style="white-space: pre-line;">
                                {{ $parkingLocation->address ?? '-' }}
                            </div>
                            <div class="info-help">Alamat lengkap lokasi parkir.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Area / Zona</div>
                            <div class="info-value">{{ $parkingLocation->area ?? '-' }}</div>
                            <div class="info-help">Area detail di lokasi parkir.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Status</div>
                            <div>
                                <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $parkingLocation->status ?? '-' }}
                                </span>
                            </div>
                            <div class="info-help mt-2">
                                {{ $isActive ? 'Lokasi dapat digunakan pada form operasional.' : 'Lokasi tidak muncul pada form operasional baru.' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Nama PIC</div>
                            <div class="info-value">{{ $parkingLocation->pic_name ?? '-' }}</div>
                            <div class="info-help">Penanggung jawab lokasi.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">No. Telepon PIC</div>
                            <div class="info-value">{{ $parkingLocation->pic_phone ?? '-' }}</div>
                            <div class="info-help">Kontak koordinasi lokasi.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="side-card mb-4 text-center">
                <div class="avatar-location">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <h5 class="fw-bold mb-1">
                    {{ $parkingLocation->location_code ?? '-' }}
                </h5>

                <p class="text-muted mb-3">
                    {{ $parkingLocation->location_name ?? '-' }}
                </p>

                <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    {{ $parkingLocation->status ?? '-' }}
                </span>
            </div>

            <div class="side-card mb-4">
                <h5 class="section-title-local">Informasi Sistem</h5>
                <p class="section-subtitle-local mb-3">
                    Ringkasan waktu pembuatan dan pembaruan data.
                </p>

                <div class="side-row">
                    <div class="side-label">ID Lokasi</div>
                    <div class="side-value">{{ $parkingLocation->id }}</div>
                </div>

                <div class="side-row">
                    <div class="side-label">Dibuat</div>
                    <div class="side-value">
                        {{ $parkingLocation->created_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>

                <div class="side-row">
                    <div class="side-label">Diperbarui</div>
                    <div class="side-value">
                        {{ $parkingLocation->updated_at?->format('d M Y H:i') ?? '-' }} WIB
                    </div>
                </div>
            </div>

            <div class="side-card">
                <h5 class="section-title-local">Aksi Lokasi</h5>
                <p class="section-subtitle-local mb-3">
                    Kelola data master lokasi parkir.
                </p>

                <div class="d-grid gap-2">
                    <a href="{{ route('parking-locations.edit', $parkingLocation) }}" class="btn btn-warning text-white rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Lokasi
                    </a>

                    <a href="{{ route('parking-locations.index') }}" class="btn btn-soft rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke List
                    </a>

                    <form
                        method="POST"
                        action="{{ route('parking-locations.destroy', $parkingLocation) }}"
                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger rounded-3 w-100">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Lokasi
                        </button>
                    </form>
                </div>

                <div class="alert alert-warning rounded-4 mt-3 mb-0">
                    Untuk data yang sudah pernah digunakan pada transaksi, lebih aman ubah status menjadi
                    <b>Tidak Aktif</b> daripada menghapus data.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection