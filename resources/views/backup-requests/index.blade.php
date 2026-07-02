@extends('layouts.app')

@section('title', 'Permintaan Barang Backup | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $authUser = Auth::user();
    $userRole = $authUser->role;

    $isPetugas = $userRole === 'petugas';
    $isManager = $userRole === 'manajer';
    $isAdminOperational = $userRole === 'admin';

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

    $activeFilterCount = 0;

    if (!empty($search)) {
        $activeFilterCount++;
    }

    if (!empty($status)) {
        $activeFilterCount++;
    }

    $waitingCountOnPage = $backupRequests->where('status', 'Menunggu Verifikasi')->count();
    $approvedCountOnPage = $backupRequests->where('status', 'Disetujui')->count();
    $processCountOnPage = $backupRequests->where('status', 'Dalam Proses')->count();
    $doneCountOnPage = $backupRequests->where('status', 'Selesai')->count();

    $operationalLocationLabel = 'Belum ditentukan';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->location_code)) {
            $operationalLocationLabel .= ' - ' . $authUser->parkingLocation->location_code;
        }
    }
@endphp

<style>
    .backup-page-title {
        color: #071b4d;
        font-size: 27px;
        font-weight: 950;
        letter-spacing: -0.4px;
        margin-bottom: 6px;
    }

    .backup-page-subtitle {
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

    .summary-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        height: 100%;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        margin-bottom: 0;
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

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .summary-icon.info {
        background: #e5f8ff;
        color: #0bb4d8;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
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

    .form-label {
        color: #071b4d;
        font-size: 14px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        min-height: 48px;
        border-radius: 13px;
        border: 1px solid #d7e3f7;
        background-color: #f8fbff;
        color: #071b4d;
        font-weight: 650;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .location-info-card {
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

    .location-label {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .location-value {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .table thead th {
        color: #5f719a;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .table tbody td {
        color: #071b4d;
        font-size: 14px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fbff;
    }

    .item-name {
        color: #071b4d;
        font-weight: 900;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-state {
        padding: 58px 16px;
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

    .role-alert {
        border-radius: 18px;
        border: none;
        padding: 18px;
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
                <h3 class="backup-page-title">Permintaan Barang Backup</h3>
                <p class="backup-page-subtitle">
                    @if ($isPetugas)
                        History permintaan backup pada lokasi operasional yang sama.
                    @elseif ($isManager)
                        Pantau permintaan barang backup dan lakukan approve atau reject sesuai kebutuhan operasional.
                    @elseif ($isAdminOperational)
                        Proses permintaan barang backup yang sudah disetujui oleh Manajer Operasional.
                    @else
                        Daftar permintaan barang backup operasional parkir.
                    @endif
                </p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($isPetugas)
                <a href="{{ route('backup-requests.create') }}" class="btn btn-primary rounded-3 px-3">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajukan Permintaan
                </a>
            @endif

            @if ($isManager)
                <a href="{{ route('report-recaps.index') }}" class="btn btn-soft rounded-3 px-3">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>
                    Laporan Rekap
                </a>
            @endif
        </div>
    </div>

    {{-- Info Lokasi Khusus Petugas --}}
    @if ($isPetugas)
        <div class="location-info-card mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="location-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <div>
                    <div class="location-label">History Lokasi Operasional</div>
                    <div class="location-value">{{ $operationalLocationLabel }}</div>
                    <div class="text-muted small">
                        Daftar di bawah ini menampilkan permintaan backup dari semua Petugas yang berada di lokasi operasional yang sama.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Info Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Total Data</div>
                        <h4 class="summary-value">{{ number_format($backupRequests->total()) }}</h4>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">
                            @if ($isAdminOperational)
                                Siap Diproses
                            @elseif ($isManager)
                                Menunggu Approval
                            @else
                                Menunggu Verifikasi
                            @endif
                        </div>

                        <h4 class="summary-value">
                            @if ($isAdminOperational)
                                {{ number_format($approvedCountOnPage) }}
                            @else
                                {{ number_format($waitingCountOnPage) }}
                            @endif
                        </h4>

                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Dalam Proses</div>
                        <h4 class="summary-value">{{ number_format($processCountOnPage) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="summary-label">Selesai</div>
                        <h4 class="summary-value">{{ number_format($doneCountOnPage) }}</h4>
                        <div class="muted-small mt-1">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alur Informasi Role --}}
    @if ($isPetugas)
        <div class="alert alert-primary role-alert mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Alur Petugas Parkir
            </div>
            Petugas dapat melihat history backup berdasarkan <b>lokasi operasional yang sama</b>.
            Namun edit dan hapus hanya dapat dilakukan oleh akun pembuat request selama status masih <b>Menunggu Verifikasi</b>.
        </div>
    @endif

    @if ($isManager)
        <div class="alert alert-primary role-alert mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Alur Manajer Operasional
            </div>
            Manajer Operasional bertugas melakukan <b>approve</b> atau <b>reject</b> permintaan backup.
            Setelah disetujui, proses penyerahan barang dilanjutkan oleh Admin Operasional.
        </div>
    @endif

    @if ($isAdminOperational)
        <div class="alert alert-info role-alert mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-info-circle-fill me-1"></i>
                Alur Admin Operasional
            </div>
            Admin Operasional memproses permintaan backup yang sudah <b>Disetujui</b> oleh Manajer Operasional,
            lalu menyelesaikan penyerahan barang dan memperbarui stok secara otomatis.
        </div>
    @endif

    {{-- Filter --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title">Filter Permintaan</h5>
                <p class="section-subtitle">
                    Cari berdasarkan nomor permintaan, barang, lokasi, petugas, status, atau prioritas.
                </p>
            </div>

            @if (!empty($search) || !empty($status))
                <a href="{{ route('backup-requests.index') }}" class="btn btn-soft rounded-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('backup-requests.index') }}" class="row g-3">
            <div class="col-md-7">
                <label class="form-label">Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Cari nomor permintaan, barang, lokasi, petugas, status, prioritas..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ ($status ?? '') === 'Menunggu Verifikasi' ? 'selected' : '' }}>
                        Menunggu Verifikasi
                    </option>
                    <option value="Disetujui" {{ ($status ?? '') === 'Disetujui' ? 'selected' : '' }}>
                        Disetujui
                    </option>
                    <option value="Ditolak" {{ ($status ?? '') === 'Ditolak' ? 'selected' : '' }}>
                        Ditolak
                    </option>
                    <option value="Dalam Proses" {{ ($status ?? '') === 'Dalam Proses' ? 'selected' : '' }}>
                        Dalam Proses
                    </option>
                    <option value="Selesai" {{ ($status ?? '') === 'Selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title">Daftar Permintaan Barang Backup</h5>
                <p class="section-subtitle">
                    @if ($isPetugas)
                        Menampilkan history permintaan backup pada lokasi operasional yang sama.
                    @elseif ($isManager)
                        Menampilkan seluruh permintaan backup untuk kebutuhan approval Manajer Operasional.
                    @elseif ($isAdminOperational)
                        Menampilkan seluruh permintaan backup untuk proses operasional barang.
                    @else
                        Menampilkan permintaan barang backup.
                    @endif
                </p>
            </div>

            <div class="text-muted small">
                Menampilkan
                <b>{{ $backupRequests->firstItem() ?? 0 }}</b>
                sampai
                <b>{{ $backupRequests->lastItem() ?? 0 }}</b>
                dari
                <b>{{ $backupRequests->total() }}</b>
                data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Permintaan</th>
                        <th>Pembuat</th>
                        <th>Barang</th>
                        <th>Lokasi Operasional</th>
                        <th>Jumlah</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($backupRequests as $requestItem)
                        @php
                            $requestLocationLabel = $requestItem->parkingLocation->location_name ?? '-';

                            if (!empty($requestItem->parkingLocation->location_code)) {
                                $requestLocationLabel .= ' - ' . $requestItem->parkingLocation->location_code;
                            }

                            $isOwner = $requestItem->user_id === $authUser->id;
                            $canPetugasModify = $isPetugas && $isOwner && $requestItem->status === 'Menunggu Verifikasi';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ $backupRequests->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $requestItem->request_number ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    ID: {{ $requestItem->id }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $requestItem->requester->full_name ?? $requestItem->requester->name ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    NIK: {{ $requestItem->requester->username ?? '-' }}
                                </div>

                                @if ($isPetugas && $isOwner)
                                    <span class="badge rounded-pill bg-primary mt-1">
                                        Request Anda
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="item-name">
                                    {{ $requestItem->backupItem->item_name ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    Kode: {{ $requestItem->backupItem->item_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $requestLocationLabel }}
                                </div>
                                <div class="muted-small">
                                    Kode: {{ $requestItem->parkingLocation->location_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ number_format($requestItem->quantity ?? 0) }}
                                    {{ $requestItem->backupItem->unit ?? '' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $priorityBadgeClass($requestItem->priority ?? '') }}">
                                    {{ $requestItem->priority ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($requestItem->status ?? '') }}">
                                    {{ $requestItem->status ?? '-' }}
                                </span>

                                @if ($isManager && $requestItem->status === 'Menunggu Verifikasi')
                                    <div class="muted-small mt-1">
                                        Perlu approval
                                    </div>
                                @endif

                                @if ($isAdminOperational && $requestItem->status === 'Disetujui')
                                    <div class="muted-small mt-1">
                                        Siap diproses
                                    </div>
                                @endif

                                @if ($isAdminOperational && $requestItem->status === 'Dalam Proses')
                                    <div class="muted-small mt-1">
                                        Siap diselesaikan
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">
                                    {{ $requestItem->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="muted-small">
                                    {{ $requestItem->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a href="{{ route('backup-requests.show', $requestItem) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    @if ($canPetugasModify)
                                        <a href="{{ route('backup-requests.edit', $requestItem) }}"
                                           class="btn btn-sm btn-warning text-white rounded-3">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('backup-requests.destroy', $requestItem) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus permintaan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if ($isPetugas && !$isOwner)
                                    <div class="muted-small mt-1">
                                        History lokasi
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada permintaan barang backup</h6>

                                    <p class="mb-3">
                                        @if ($isPetugas)
                                            Belum ada permintaan barang backup pada lokasi operasional Anda.
                                        @elseif ($isManager)
                                            Belum ada permintaan backup yang perlu dipantau atau disetujui.
                                        @elseif ($isAdminOperational)
                                            Belum ada permintaan backup yang perlu diproses.
                                        @else
                                            Belum ada permintaan barang backup.
                                        @endif
                                    </p>

                                    @if ($isPetugas)
                                        <a href="{{ route('backup-requests.create') }}" class="btn btn-primary rounded-3">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Ajukan Permintaan Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($backupRequests->hasPages())
            <div class="mt-4">
                {{ $backupRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection