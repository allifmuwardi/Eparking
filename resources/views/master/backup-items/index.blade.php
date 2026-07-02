@extends('layouts.app')

@section('title', 'Master Barang Backup | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $statusBadgeClass = function ($status, $stock) {
        if (($stock ?? 0) <= 0) {
            return 'bg-secondary';
        }

        return $status === 'Tersedia' ? 'bg-success' : 'bg-secondary';
    };
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                 style="width: 56px; height: 56px;">
                <i class="bi bi-box-seam fs-3"></i>
            </div>

            <div>
                <h3 class="fw-bold mb-1">Master Barang Backup</h3>
                <p class="text-muted mb-0">
                    Kelola stok barang backup untuk kebutuhan operasional parkir.
                </p>
            </div>
        </div>

        <a href="{{ route('backup-items.create') }}" class="btn btn-primary rounded-3">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Barang Backup
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Total Barang</div>
                <h4 class="fw-bold mb-0">{{ number_format($summary['total'] ?? 0) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Barang Tersedia</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($summary['available'] ?? 0) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Tidak Tersedia</div>
                <h4 class="fw-bold text-secondary mb-0">{{ number_format($summary['not_available'] ?? 0) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Total Stok</div>
                <h4 class="fw-bold text-primary mb-0">{{ number_format($summary['total_stock'] ?? 0) }}</h4>
            </div>
        </div>
    </div>

    <div class="alert alert-primary border-0 rounded-4 mb-4">
        <div class="fw-bold mb-1">
            <i class="bi bi-info-circle-fill me-1"></i>
            Catatan Stok
        </div>
        Status barang backup otomatis mengikuti stok. Jika stok <b>0</b>, barang akan menjadi <b>Tidak Tersedia</b> dan tidak bisa dipilih oleh Petugas saat membuat permintaan backup.
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Filter Barang Backup</h5>
                <p class="text-muted small mb-0">
                    Cari berdasarkan kode, nama barang, kategori, satuan, lokasi penyimpanan, atau status.
                </p>
            </div>

            @if (!empty($search))
                <a href="{{ route('backup-items.index') }}" class="btn btn-light border rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('backup-items.index') }}" class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control rounded-3"
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
    </div>

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Barang Backup</h5>
                <p class="text-muted small mb-0">
                    Menampilkan data barang backup yang tersedia untuk operasional.
                </p>
            </div>

            <div class="text-muted small">
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
            <table class="table align-middle">
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
                        <th class="text-end" style="width: 210px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        @php
                            $realStatus = ($item->stock ?? 0) > 0 ? 'Tersedia' : 'Tidak Tersedia';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="fw-bold text-primary">{{ $item->item_code ?? '-' }}</span>
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $item->item_name ?? '-' }}</div>
                                <div class="text-muted small">
                                    ID: {{ $item->id }}
                                </div>
                            </td>

                            <td>{{ $item->category ?? '-' }}</td>

                            <td>
                                <span class="fw-bold {{ ($item->stock ?? 0) <= 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($item->stock ?? 0) }}
                                </span>
                            </td>

                            <td>{{ $item->unit ?? '-' }}</td>

                            <td>{{ $item->storage_location ?? '-' }}</td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($realStatus, $item->stock) }}">
                                    {{ $realStatus }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item->updated_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $item->updated_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a href="{{ route('backup-items.show', $item) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    <a href="{{ route('backup-items.edit', $item) }}"
                                       class="btn btn-sm btn-warning text-white rounded-3">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('backup-items.destroy', $item) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus barang backup ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger rounded-3">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <h6 class="fw-bold mb-1">Belum ada barang backup</h6>
                                    <p class="mb-3">
                                        Tambahkan barang backup pertama untuk kebutuhan operasional parkir.
                                    </p>

                                    <a href="{{ route('backup-items.create') }}" class="btn btn-primary rounded-3">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Tambah Barang
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