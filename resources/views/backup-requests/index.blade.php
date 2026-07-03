@extends('layouts.app')

@section('title', 'Permintaan Backup Barang | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Permintaan Backup Barang')
@section('page_subtitle', 'Pengajuan, verifikasi, proses, dan monitoring kebutuhan barang backup')

@section('content')
@php
    $authUser = Auth::user();
    $userRole = $authUser->role ?? '';

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

    $searchValue = $search ?? request('search');
    $statusValue = $status ?? request('status');

    $waitingCountOnPage = $backupRequests->where('status', 'Menunggu Verifikasi')->count();
    $approvedCountOnPage = $backupRequests->where('status', 'Disetujui')->count();
    $processCountOnPage = $backupRequests->where('status', 'Dalam Proses')->count();
    $doneCountOnPage = $backupRequests->where('status', 'Selesai')->count();

    $operationalLocationLabel = 'Seluruh Lokasi / Sesuai Akses';

    if ($authUser->parkingLocation) {
        $operationalLocationLabel = $authUser->parkingLocation->location_name ?? '-';

        if (!empty($authUser->parkingLocation->location_code)) {
            $operationalLocationLabel .= ' (' . $authUser->parkingLocation->location_code . ')';
        }
    }

    $roleLabel = match ($userRole) {
        'petugas' => 'Petugas Parkir',
        'manajer' => 'Manajer Operasional',
        'admin' => 'Admin Operasional',
        default => 'Pengguna',
    };

    $activeFilterCount = 0;

    if (!empty($searchValue)) {
        $activeFilterCount++;
    }

    if (!empty($statusValue)) {
        $activeFilterCount++;
    }
@endphp

<style>
    .backup-title {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .backup-subtitle {
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

    .flow-card {
        border-radius: 20px;
        border: 1px solid #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
        padding: 20px;
    }

    .flow-step {
        border-radius: 18px;
        border: 1px solid #d7e3f7;
        background: rgba(255, 255, 255, 0.9);
        padding: 16px;
        height: 100%;
    }

    .flow-icon {
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
        font-weight: 900;
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
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 28px;
        font-weight: 950;
        margin-bottom: 0;
        line-height: 1.1;
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

    .summary-icon.primary { background: #eaf3ff; color: #0d6efd; }
    .summary-icon.warning { background: #fff6dc; color: #d99a00; }
    .summary-icon.success { background: #e7f7ee; color: #198754; }
    .summary-icon.info { background: #e5f8ff; color: #0bb4d8; }

    .backup-table thead th {
        color: #071b4d;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #f4f8ff;
        border-bottom: 1px solid #d7e3f7;
        white-space: nowrap;
    }

    .backup-table tbody td {
        color: #071b4d;
        font-size: 13px;
        font-weight: 650;
        border-bottom: 1px solid #edf3fc;
        vertical-align: middle;
    }

    .backup-table tbody tr:hover {
        background: #f8fbff;
    }

    .table-title-link {
        color: #0d6efd;
        font-weight: 950;
    }

    .table-title-link:hover {
        color: #0649bd;
    }

    .muted-small {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
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
        .backup-title {
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
                <h3 class="backup-title">Permintaan Backup Barang</h3>
                <p class="backup-subtitle">
                    Kelola pengajuan barang backup operasional parkir berdasarkan role dan hak akses pengguna.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            @if ($isPetugas)
                <a href="{{ route('backup-requests.create') }}" class="btn btn-primary rounded-3 px-3">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajukan Backup
                </a>
            @endif

            @if ($isManager)
                <a href="{{ route('report-recaps.index') }}" class="btn btn-primary rounded-3 px-3">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>
                    Laporan Rekap
                </a>
            @endif
        </div>
    </div>

    <div class="location-info-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="location-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>

            <div>
                <div class="location-label">Akses Pengguna</div>
                <div class="location-value">{{ $roleLabel }} — {{ $operationalLocationLabel }}</div>
                <div class="text-muted small fw-semibold">
                    @if ($isPetugas)
                        Petugas dapat mengajukan backup barang dan melihat history permintaan pada lokasi operasional yang sama.
                    @elseif ($isManager)
                        Manajer melakukan verifikasi, menyetujui, atau menolak permintaan backup barang dari Petugas.
                    @elseif ($isAdminOperational)
                        Admin Operasional memproses permintaan yang sudah disetujui dan menyelesaikan penyerahan barang.
                    @else
                        Data ditampilkan berdasarkan hak akses pengguna.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flow-card mb-4">
        <div class="mb-3">
            <h5 class="section-title-local">Alur Permintaan Backup Barang</h5>
            <p class="section-subtitle-local">
                Alur ini digunakan untuk memastikan pengajuan barang backup tercatat, diverifikasi, diproses, dan stok barang terkontrol.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-send-check"></i>
                    </div>
                    <div class="fw-bold">1. Pengajuan</div>
                    <div class="small text-muted fw-semibold mt-1">
                        Petugas membuat permintaan backup barang.
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="fw-bold">2. Verifikasi</div>
                    <div class="small text-muted fw-semibold mt-1">
                        Manajer menyetujui atau menolak permintaan.
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="fw-bold">3. Proses Admin</div>
                    <div class="small text-muted fw-semibold mt-1">
                        Admin menyiapkan barang backup.
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="flow-step">
                    <div class="flow-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="fw-bold">4. Selesai</div>
                    <div class="small text-muted fw-semibold mt-1">
                        Barang diserahkan dan stok dikurangi otomatis.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Permintaan</div>
                        <h4 class="summary-value">{{ number_format($backupRequests->total()) }}</h4>
                        <div class="summary-help">Seluruh data sesuai filter</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Menunggu Verifikasi</div>
                        <h4 class="summary-value text-warning">{{ number_format($waitingCountOnPage) }}</h4>
                        <div class="summary-help">Berdasarkan halaman ini</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Disetujui</div>
                        <h4 class="summary-value text-success">{{ number_format($approvedCountOnPage) }}</h4>
                        <div class="summary-help">Siap diproses Admin</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-check2-square"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Dalam Proses / Selesai</div>
                        <h4 class="summary-value text-info">{{ number_format($processCountOnPage + $doneCountOnPage) }}</h4>
                        <div class="summary-help">Diproses atau selesai</div>
                    </div>

                    <div class="summary-icon info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h5 class="section-title-local">Filter Permintaan Backup</h5>
                <p class="section-subtitle-local">
                    Cari berdasarkan nomor permintaan, barang, lokasi, petugas, prioritas, status, atau alasan kebutuhan.
                </p>
            </div>

            @if ($activeFilterCount > 0)
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
                    value="{{ $searchValue }}"
                    class="form-control"
                    placeholder="Cari nomor, barang, lokasi, petugas, alasan..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ $statusValue === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Disetujui" {{ $statusValue === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ $statusValue === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Dalam Proses" {{ $statusValue === 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="Selesai" {{ $statusValue === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
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
                <h5 class="section-title-local">Daftar Permintaan Backup Barang</h5>
                <p class="section-subtitle-local">
                    Menampilkan permintaan backup barang sesuai hak akses pengguna.
                </p>
            </div>

            <div class="text-muted small fw-semibold">
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
            <table class="table backup-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. Request</th>
                        <th>Barang</th>
                        <th>Lokasi</th>
                        <th>Pemohon</th>
                        <th class="text-end">Jumlah</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($backupRequests as $request)
                        @php
                            $requesterName = $request->requester->full_name ?? $request->requester->name ?? '-';
                            $isOwner = (int) ($request->user_id ?? 0) === (int) ($authUser->id ?? 0);
                            $canEditDelete = $isPetugas && $isOwner && ($request->status ?? '') === 'Menunggu Verifikasi';
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ ($backupRequests->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('backup-requests.show', $request) }}" class="table-title-link">
                                    {{ $request->request_number ?? '-' }}
                                </a>

                                @if ($isOwner)
                                    <div>
                                        <span class="badge rounded-pill bg-primary mt-1">Permintaan Anda</span>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">{{ $request->backupItem->item_name ?? '-' }}</div>
                                <div class="muted-small">
                                    Kode: {{ $request->backupItem->item_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $request->parkingLocation->location_name ?? '-' }}</div>
                                <div class="muted-small">
                                    Kode: {{ $request->parkingLocation->location_code ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $requesterName }}</div>
                                <div class="muted-small">
                                    NIK: {{ $request->requester->username ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold">{{ number_format($request->quantity ?? 0) }}</div>
                                <div class="muted-small">{{ $request->backupItem->unit ?? 'unit' }}</div>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $priorityBadgeClass($request->priority ?? '') }}">
                                    {{ $request->priority ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $statusBadgeClass($request->status ?? '') }}">
                                    {{ $request->status ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $request->created_at?->format('d M Y') ?? '-' }}</div>
                                <div class="muted-small">{{ $request->created_at?->format('H:i') ?? '-' }} WIB</div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                    <a href="{{ route('backup-requests.show', $request) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>

                                    @if ($canEditDelete)
                                        <a href="{{ route('backup-requests.edit', $request) }}"
                                           class="btn btn-sm btn-warning text-white rounded-3">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('backup-requests.destroy', $request) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus permintaan backup ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
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

                                    <h6 class="fw-bold mb-1 text-dark">Belum ada permintaan backup barang</h6>

                                    <p class="mb-3">
                                        Belum ada data permintaan backup barang yang tercatat pada akses ini.
                                    </p>

                                    @if ($isPetugas)
                                        <a href="{{ route('backup-requests.create') }}" class="btn btn-primary rounded-3">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Ajukan Backup Pertama
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