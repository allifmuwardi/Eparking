@extends('layouts.app')

@section('title', 'Laporan Rekap')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-primary',
            'Selesai Ditangani', 'Selesai' => 'bg-success',
            'Disetujui' => 'bg-success',
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
@endphp

<div class="container-fluid">

    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-file-earmark-bar-graph fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Laporan Rekap Operasional</h3>
                    <p class="text-muted mb-0">
                        Rekap laporan kendala, traffic harian, dan permintaan backup ELITE Parkir.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Filter Data Rekap</h5>
                    <p class="text-muted mb-0 small">
                        Gunakan filter untuk menampilkan data berdasarkan periode, lokasi parkir, dan status.
                    </p>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-funnel"></i> Filter Laporan
                </span>
            </div>

            <form method="GET" action="{{ route('report-recaps.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control"
                               value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Lokasi Parkir</label>
                        <select name="parking_location_id" class="form-select">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ request('parking_location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->location_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>

                            <optgroup label="Status Laporan Kendala">
                                @foreach ($issueStatuses as $item)
                                    <option value="{{ $item }}" {{ request('status') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Status Permintaan Backup">
                                @foreach ($backupStatuses as $item)
                                    <option value="{{ $item }}" {{ request('status') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-md-12 d-flex flex-wrap gap-2 pt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Terapkan Filter
                        </button>

                        <a href="{{ route('report-recaps.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </a>

                        <a href="{{ route('report-recaps.export.issue-reports', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Kendala Excel
                        </a>

                        <a href="{{ route('report-recaps.export.traffic-reports', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Traffic Excel
                        </a>

                        <a href="{{ route('report-recaps.export.backup-requests', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export Backup Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Laporan Kendala</p>
                            <h2 class="fw-bold mb-1">{{ $totalIssueReports }}</h2>
                            <small class="text-muted">Data laporan kendala sesuai filter aktif</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary-subtle text-primary"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Traffic Harian</p>
                            <h2 class="fw-bold mb-1">{{ $totalTrafficReports }}</h2>
                            <small class="text-muted">Data traffic harian sesuai filter aktif</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-4 bg-info-subtle text-info"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-bar-chart-line fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Permintaan Backup</p>
                            <h2 class="fw-bold mb-1">{{ $totalBackupRequests }}</h2>
                            <small class="text-muted">Data permintaan backup sesuai filter aktif</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-4 bg-warning-subtle text-warning"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Laporan Kendala --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Rekap Laporan Kendala</h5>
                    <p class="text-muted small mb-0">
                        Menampilkan data laporan kendala berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.issue-reports', request()->query()) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
            </div>
        </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>No Laporan</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($issueReports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <span class="fw-semibold text-primary">
                                        {{ $report->report_number ?? $report->report_code ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $report->reporter->full_name ?? $report->reporter->name ?? '-' }}
                                </td>

                                <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>

                                <td>
                                    <span class="badge rounded-pill {{ $priorityBadgeClass($report->priority ?? '') }}">
                                        {{ $report->priority ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $statusBadgeClass($report->status ?? '') }}">
                                        {{ $report->status ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data laporan kendala sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekap Traffic Harian --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Rekap Traffic Harian</h5>
                    <p class="text-muted small mb-0">
                        Menampilkan data traffic harian berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.traffic-reports', request()->query()) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
            </div>
        </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th class="text-end">Masuk</th>
                            <th class="text-end">Keluar</th>
                            <th class="text-end">Total Kendaraan</th>
                            <th class="text-end">Transaksi</th>
                            <th class="text-end">Pendapatan</th>
                            <th class="text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trafficReports as $index => $traffic)
                            @php
                                $vehicleIn = $traffic->total_vehicle_in ?? 0;
                                $vehicleOut = $traffic->total_vehicle_out ?? 0;

                                $totalVehicle =
                                    ($traffic->car_count ?? 0)
                                    + ($traffic->motorcycle_count ?? 0)
                                    + ($traffic->other_vehicle_count ?? 0);

                                $income = $traffic->total_revenue ?? 0;

                                $petugasName = $traffic->user->full_name
                                    ?? $traffic->user->name
                                    ?? '-';
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $petugasName }}
                                    </div>
                                    <small class="text-muted">
                                        NIK: {{ $traffic->user->username ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $traffic->parkingLocation->location_name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        Kode: {{ $traffic->parkingLocation->location_code ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $traffic->report_date ? \Carbon\Carbon::parse($traffic->report_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $traffic->shift ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">{{ number_format($vehicleIn) }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">{{ number_format($vehicleOut) }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">{{ number_format($totalVehicle) }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold">{{ number_format($traffic->total_transaction ?? 0) }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($income, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('traffic-reports.show', $traffic) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data traffic harian sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekap Permintaan Backup --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Rekap Permintaan Backup</h5>
                    <p class="text-muted small mb-0">
                        Menampilkan data permintaan backup berdasarkan filter yang sedang aktif.
                    </p>
                </div>

                <a href="{{ route('report-recaps.export.backup-requests', request()->query()) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
            </div>
        </div>

        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Petugas</th>
                            <th>Lokasi</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backupRequests as $index => $backup)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $backup->requester->full_name ?? $backup->requester->name ?? '-' }}
                                </td>

                                <td>{{ $backup->parkingLocation->location_name ?? '-' }}</td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $backup->backupItem->item_name ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="fw-bold">{{ $backup->quantity ?? 0 }}</span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $priorityBadgeClass($backup->priority ?? '') }}">
                                        {{ $backup->priority ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill {{ $statusBadgeClass($backup->status ?? '') }}">
                                        {{ $backup->status ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data permintaan backup sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection