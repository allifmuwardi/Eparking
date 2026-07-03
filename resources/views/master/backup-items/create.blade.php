@extends('layouts.app')

@section('title', 'Tambah Barang Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Tambah Barang Backup')
@section('page_subtitle', 'Input master barang backup operasional parkir')

@section('content')
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

    .form-section-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 24px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .field-helper {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
        display: block;
    }

    .side-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    .guide-card {
        border-radius: 18px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px;
    }

    .guide-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .guide-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .guide-value {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .example-box {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 14px;
        margin-bottom: 12px;
    }

    .example-box:last-child {
        margin-bottom: 0;
    }

    .example-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        margin-bottom: 3px;
    }

    .example-value {
        color: #071b4d;
        font-size: 14px;
        font-weight: 950;
    }

    .status-rule {
        border-radius: 16px;
        border: 1px solid #d7e3f7;
        background: #f8fbff;
        padding: 14px;
        margin-bottom: 12px;
    }

    .status-rule:last-child {
        margin-bottom: 0;
    }

    .action-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
    }

    textarea.form-control {
        min-height: 130px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .form-section-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-plus-circle"></i>
            </div>

            <div>
                <h3 class="page-title-local">Tambah Barang Backup</h3>
                <p class="page-subtitle-local">
                    Tambahkan master barang backup untuk kebutuhan operasional parkir.
                </p>
            </div>
        </div>

        <a href="{{ route('backup-items.index') }}" class="btn btn-soft rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('backup-items.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-section-card">
                    <div class="mb-4">
                        <h5 class="section-title-local">Informasi Barang</h5>
                        <p class="section-subtitle-local">
                            Lengkapi data barang, stok, satuan, dan lokasi penyimpanan.
                        </p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Kode Barang <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="item_code"
                                value="{{ old('item_code') }}"
                                class="form-control @error('item_code') is-invalid @enderror"
                                placeholder="Contoh: BRG001"
                                required
                            >

                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Gunakan kode unik untuk barang backup.
                            </span>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">
                                Nama Barang <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="item_name"
                                value="{{ old('item_name') }}"
                                class="form-control @error('item_name') is-invalid @enderror"
                                placeholder="Contoh: Printer Tiket"
                                required
                            >

                            @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Nama barang yang mudah dikenali petugas dan admin.
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>

                            <input
                                type="text"
                                name="category"
                                value="{{ old('category') }}"
                                class="form-control @error('category') is-invalid @enderror"
                                placeholder="Contoh: Hardware / Sparepart"
                            >

                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Kelompokkan barang agar mudah dicari.
                            </span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Stok <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="stock"
                                value="{{ old('stock', 0) }}"
                                min="0"
                                class="form-control @error('stock') is-invalid @enderror"
                                required
                            >

                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Status otomatis mengikuti stok.
                            </span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Satuan <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="unit"
                                value="{{ old('unit', 'Unit') }}"
                                class="form-control @error('unit') is-invalid @enderror"
                                placeholder="Unit / Pcs / Set"
                                required
                            >

                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Contoh: Unit, Pcs, Set.
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Lokasi Penyimpanan</label>

                            <input
                                type="text"
                                name="storage_location"
                                value="{{ old('storage_location') }}"
                                class="form-control @error('storage_location') is-invalid @enderror"
                                placeholder="Contoh: Gudang Operasional / Office Parking"
                            >

                            @error('storage_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Isi lokasi penyimpanan fisik barang backup.
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi</label>

                            <textarea
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Tambahkan keterangan barang backup jika diperlukan..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <span class="field-helper">
                                Deskripsi membantu menjelaskan fungsi atau kondisi barang.
                            </span>
                        </div>
                    </div>
                </div>

                <div class="action-card mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Simpan Barang Backup</h6>
                            <p class="text-muted small mb-0">
                                Pastikan data barang dan stok sudah benar sebelum disimpan.
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('backup-items.index') }}" class="btn btn-soft rounded-3 px-4">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Barang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-card sticky-top" style="top: 120px;">
                    <div class="guide-card mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="guide-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>

                            <div>
                                <div class="guide-label">Panduan Barang</div>
                                <div class="guide-value">Stok Mengatur Status</div>
                                <div class="text-muted small fw-semibold">
                                    Barang backup dengan stok kosong tidak akan muncul saat Petugas membuat permintaan backup.
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title-local">Status Otomatis</h5>
                    <p class="section-subtitle-local mb-3">
                        Sistem menentukan status barang berdasarkan stok.
                    </p>

                    <div class="status-rule">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge rounded-pill bg-success">Tersedia</span>
                            <span class="fw-bold small">Stok lebih dari 0</span>
                        </div>
                        <div class="text-muted small fw-semibold">
                            Barang dapat dipilih oleh Petugas saat membuat permintaan backup.
                        </div>
                    </div>

                    <div class="status-rule">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge rounded-pill bg-secondary">Tidak Tersedia</span>
                            <span class="fw-bold small">Stok sama dengan 0</span>
                        </div>
                        <div class="text-muted small fw-semibold">
                            Barang tidak bisa dipilih pada permintaan backup baru.
                        </div>
                    </div>

                    <hr>

                    <h5 class="section-title-local">Contoh Pengisian</h5>
                    <p class="section-subtitle-local mb-3">
                        Gunakan format yang konsisten agar mudah dibaca.
                    </p>

                    <div class="example-box">
                        <div class="example-label">Kode Barang</div>
                        <div class="example-value">BRG001</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Nama Barang</div>
                        <div class="example-value">Printer Tiket</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Kategori</div>
                        <div class="example-value">Hardware</div>
                    </div>

                    <div class="example-box">
                        <div class="example-label">Satuan</div>
                        <div class="example-value">Unit / Pcs / Set</div>
                    </div>

                    <div class="alert alert-warning rounded-4 mt-3 mb-0">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Penting
                        </div>
                        Pastikan stok awal sesuai kondisi gudang agar proses permintaan backup lebih akurat.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection