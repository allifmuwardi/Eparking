@extends('layouts.app')

@section('title', 'Master Barang Backup | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Master Barang Backup')
@section('page_subtitle', 'Pengelolaan stok barang backup operasional parkir')

@section('content')
@php
    $statusBadgeClass = function ($status, $stock) {
        if (($stock ?? 0) <= 0) {
            return 'bg-secondary';
        }

        return $status === 'Tersedia' ? 'bg-success' : 'bg-secondary';
    };

    $searchValue = $search ?? request('search');
    $activeFilterCount = !empty($searchValue) ? 1 : 0;
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

    .master-info-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .master-info-icon {
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

    .master-info-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .master-info-value {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .summary-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 0;
    }

    .summary-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .summary-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .summary-icon.secondary {
        background: #eef2f7;
        color: #64748b;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .stock-alert {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.10), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 18px 20px;
    }

    .stock-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 15px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .backup-item-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .backup-item-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .backup-item-table tbody tr:hover {
        background: #f8fbff;
    }

    .item-code-link {
        color: #0d6efd;
        font-size: 14px;
        font-weight: 950;
        text-decoration: none;
    }

    .item-code-link:hover {
        color: #0649bd;
        text-decoration: underline;
    }

    .item-name {
        color: #071b4d;
        font-size: 14px;
        font-weight: 900;
        margin-bottom: 2px;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
    }

    .stock-number {
        font-size: 15px;
        font-weight: 950;
    }

    .empty-state {
        padding: 56px 16px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: 24px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin: 0 auto 16px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .summary-value {
            font-size: 24px;
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
                <h3 class="page-title-local">Master Barang Backup</h3>
                <p class="page-subtitle-local">
                    Kelola stok barang backup yang digunakan untuk kebutuhan operasional parkir.
                </p>
            </div>
        </div>

        <a href="{{ route('backup-items.create') }}" class="btn btn-primary rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Barang Backup
        </a>
    </div>

    <div class="master-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="master-info-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>

            <div>
                <div class="master-info-label">Master Data Operasional</div>
                <div class="master-info-value">Barang Backup</div>
                <div class="text-muted small fw-semibold">
                    Barang dengan stok tersedia dapat dipilih oleh Petugas saat membuat permintaan backup barang.
                    Jika stok menjadi 0, status barang otomatis dianggap Tidak Tersedia.
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Barang</div>
                        <h4 class="summary-value">{{ number_format($summary['total'] ?? 0) }}</h4>
                        <div class="summary-help">Seluruh master barang</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Barang Tersedia</div>
                        <h4 class="summary-value text-success">{{ number_format($summary['available'] ?? 0) }}</h4>
                        <div class="summary-help">Stok lebih dari 0</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Tidak Tersedia</div>
                        <h4 class="summary-value text-secondary">{{ number_format($summary['not_available'] ?? 0) }}</h4>
                        <div class="summary-help">Stok kosong atau nonaktif</div>
                    </div>

                    <div class="summary-icon secondary">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Stok</div>
                        <h4 class="summary-value text-primary">{{ number_format($summary['total_stock'] ?? 0) }}</h4>
                        <div class="summary-help">Akumulasi seluruh stok</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="stock-alert mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="stock-alert-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>

            <div>
                <div class="fw-bold text-primary mb-1">Catatan Stok Barang</div>
                <div class="text-muted small fw-semibold">
                    Status barang backup otomatis mengikuti stok. Jika stok <b>0</b>, barang menjadi
                    <b>Tidak Tersedia</b> dan tidak bisa dipilih oleh Petugas saat membuat permintaan backup.
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Barang Backup</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan kode barang, nama barang, kategori, satuan, lokasi penyimpanan, atau status.
                </p>
            </div>

            @if (!empty($searchValue))
                <a href="{{ route('backup-items.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('backup-items.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>

                <input
                    type="text"
                    name="search"
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari kode barang, nama barang, kategori, lokasi penyimpanan..."
                >
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>

                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>
            </div>
        </form>

        @if ($activeFilterCount > 0)
            <div class="mt-3 small text-muted fw-semibold">
                <i class="bi bi-funnel me-1"></i>
                Filter aktif: <b>{{ $activeFilterCount }}</b>
            </div>
        @endif
    </div>

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Daftar Barang Backup</h5>
                <p class="section-subtitle-local">
                    Menampilkan data barang backup yang tersedia untuk mendukung operasional parkir.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
                Menampilkan
                <b>{{ $items->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $items->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $items->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table backup-item-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Lokasi Simpan</th>
                        <th>Status</th>
                        <th>Diperbarui</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        @php
                            $realStatus = ($item->stock ?? 0) > 0 ? 'Tersedia' : 'Tidak Tersedia';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($items->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('backup-items.show', $item) }}" class="item-code-link">
                                    {{ $item->item_code ?? '-' }}
                                </a>

                                <div class="muted-small">
                                    ID: {{ $item->id }}
                                </div>
                            </td>

                            <td>
                                <div class="item-name">
                                    {{ $item->item_name ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    {{ $item->description ? \Illuminate\Support\Str::limit($item->description, 60) : 'Tidak ada deskripsi' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $item->category ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="stock-number {{ ($item->stock ?? 0) <= 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($item->stock ?? 0) }}
                                </span>
                            </td>

                            <td>
                                {{ $item->unit ?? '-' }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->storage_location ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($realStatus, $item->stock) }}">
                                    {{ $realStatus }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $item->updated_at?->format('d M Y') ?? '-' }}
                                </div>

                                <div class="muted-small">
                                    {{ $item->updated_at?->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a
                                        href="{{ route('backup-items.show', $item) }}"
                                        class="btn btn-sm btn-outline-primary rounded-3"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('backup-items.edit', $item) }}"
                                        class="btn btn-sm btn-warning text-white rounded-3"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('backup-items.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus barang backup ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger rounded-3">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">
                                        Belum ada barang backup
                                    </h6>

                                    <p class="mb-3">
                                        Tambahkan barang backup pertama untuk kebutuhan operasional parkir.
                                    </p>

                                    <a href="{{ route('backup-items.create') }}" class="btn btn-primary rounded-3">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Tambah Barang Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection