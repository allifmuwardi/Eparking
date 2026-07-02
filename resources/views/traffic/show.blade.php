@extends('layouts.app')

@section('title', 'Detail Traffic Harian | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $totalVehicle =
        ($trafficReport->car_count ?? 0)
        + ($trafficReport->motorcycle_count ?? 0)
        + ($trafficReport->other_vehicle_count ?? 0);
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-bar-chart-line fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Detail Traffic Harian</h3>
                    <p class="text-muted mb-0">
                        Detail laporan traffic operasional parkir harian.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('traffic-reports.edit', $trafficReport) }}" class="btn btn-warning text-white rounded-3">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>

            <a href="{{ route('traffic-reports.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Kendaraan Masuk</div>
                <h3 class="fw-bold text-primary mb-0">
                    {{ number_format($trafficReport->total_vehicle_in ?? 0) }}
                </h3>
                <div class="text-muted small mt-1">kendaraan</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Kendaraan Keluar</div>
                <h3 class="fw-bold text-primary mb-0">
                    {{ number_format($trafficReport->total_vehicle_out ?? 0) }}
                </h3>
                <div class="text-muted small mt-1">kendaraan</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Total Transaksi</div>
                <h3 class="fw-bold text-warning mb-0">
                    {{ number_format($trafficReport->total_transaction ?? 0) }}
                </h3>
                <div class="text-muted small mt-1">transaksi</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="page-card p-4 h-100">
                <div class="text-muted small mb-1">Total Pendapatan</div>
                <h5 class="fw-bold text-success mb-0">
                    Rp {{ number_format($trafficReport->total_revenue ?? 0, 0, ',', '.') }}
                </h5>
                <div class="text-muted small mt-1">pendapatan shift</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Konten Kiri --}}
        <div class="col-lg-8">

            {{-- Informasi Laporan --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Informasi Laporan</h5>
                    <p class="text-muted small mb-0">
                        Informasi utama laporan traffic harian.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Tanggal Laporan</div>
                            <div class="fw-semibold">
                                {{ $trafficReport->report_date?->format('d M Y') ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Tanggal operasional traffic
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Shift</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $trafficReport->shift ?? '-' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                Shift petugas parkir
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Lokasi Parkir</div>
                            <div class="fw-semibold">
                                {{ $trafficReport->parkingLocation->location_name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Kode: {{ $trafficReport->parkingLocation->location_code ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Area / Zona</div>
                            <div class="fw-semibold">
                                {{ $trafficReport->parkingLocation->area ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Area operasional parkir
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small">Petugas Input</div>
                            <div class="fw-semibold">
                                {{ $trafficReport->user->full_name ?? $trafficReport->user->name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Username: {{ $trafficReport->user->username ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Kendaraan --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Rincian Kendaraan</h5>
                    <p class="text-muted small mb-0">
                        Rincian jumlah kendaraan berdasarkan kategori.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 text-center h-100">
                            <div class="text-muted small">Mobil</div>
                            <h4 class="fw-bold text-primary mb-0">
                                {{ number_format($trafficReport->car_count ?? 0) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 text-center h-100">
                            <div class="text-muted small">Motor</div>
                            <h4 class="fw-bold text-primary mb-0">
                                {{ number_format($trafficReport->motorcycle_count ?? 0) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 text-center h-100">
                            <div class="text-muted small">Lainnya</div>
                            <h4 class="fw-bold text-primary mb-0">
                                {{ number_format($trafficReport->other_vehicle_count ?? 0) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 text-center h-100 bg-primary bg-opacity-10">
                            <div class="text-muted small">Total Kategori</div>
                            <h4 class="fw-bold text-primary mb-0">
                                {{ number_format($totalVehicle) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan Operasional --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Catatan Operasional</h5>
                    <p class="text-muted small mb-0">
                        Catatan kondisi operasional selama shift berlangsung.
                    </p>
                </div>

                @if ($trafficReport->notes)
                    <div class="border rounded-4 p-3" style="white-space: pre-line;">
                        {{ $trafficReport->notes }}
                    </div>
                @else
                    <div class="text-center text-muted py-5 border rounded-4">
                        <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
                        Tidak ada catatan operasional.
                    </div>
                @endif
            </div>

            {{-- Foto Dokumentasi --}}
            <div class="page-card p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Foto Dokumentasi</h5>
                    <p class="text-muted small mb-0">
                        Dokumentasi pendukung laporan traffic harian.
                    </p>
                </div>

                @if ($trafficReport->photo)
                    <a href="{{ asset('storage/' . $trafficReport->photo) }}" target="_blank">
                        <img
                            src="{{ asset('storage/' . $trafficReport->photo) }}"
                            alt="Foto Traffic Harian"
                            class="img-fluid rounded-4 border"
                            style="max-height: 420px; object-fit: cover;"
                        >
                    </a>
                @else
                    <div class="text-center text-muted py-5 border rounded-4">
                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                        Tidak ada foto dokumentasi.
                    </div>
                @endif
            </div>
        </div>

        {{-- Konten Kanan --}}
        <div class="col-lg-4">

            {{-- Informasi Sistem --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Informasi Data</h5>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th class="text-muted small ps-0">ID Data</th>
                        <td class="text-end">{{ $trafficReport->id }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Dibuat Pada</th>
                        <td class="text-end">
                            {{ $trafficReport->created_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Diperbarui</th>
                        <td class="text-end">
                            {{ $trafficReport->updated_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Ringkasan Operasional --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Ringkasan Operasional</h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Kendaraan Masuk</span>
                        <span class="fw-bold">{{ number_format($trafficReport->total_vehicle_in ?? 0) }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Kendaraan Keluar</span>
                        <span class="fw-bold">{{ number_format($trafficReport->total_vehicle_out ?? 0) }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 100%;"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Total Transaksi</span>
                        <span class="fw-bold">{{ number_format($trafficReport->total_transaction ?? 0) }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 100%;"></div>
                    </div>
                </div>

                <div class="border rounded-4 p-3 bg-success bg-opacity-10">
                    <div class="text-muted small">Total Pendapatan</div>
                    <div class="fw-bold text-success">
                        Rp {{ number_format($trafficReport->total_revenue ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="page-card p-4">
                <h5 class="fw-bold mb-3">Aksi Data</h5>

                <div class="d-grid gap-2">
                    <a href="{{ route('traffic-reports.edit', $trafficReport) }}" class="btn btn-warning text-white rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Traffic
                    </a>

                    <a href="{{ route('traffic-reports.index') }}" class="btn btn-light border rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke List
                    </a>

                    <form method="POST"
                          action="{{ route('traffic-reports.destroy', $trafficReport) }}"
                          onsubmit="return confirm('Yakin ingin menghapus laporan traffic ini?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger rounded-3 w-100">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Traffic
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection