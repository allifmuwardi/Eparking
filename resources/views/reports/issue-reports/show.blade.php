@extends('layouts.app')

@section('title', 'Detail Laporan Kendala | Sistem Penanganan Kendala Parkir')

@section('content')
@php
    $statusBadgeClass = function ($status) {
        return match ($status) {
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Dalam Proses' => 'bg-info text-dark',
            'Menunggu Informasi' => 'bg-primary',
            'Selesai Ditangani' => 'bg-success',
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

    $totalFollowUp = $issueReport->followUps->count();
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Detail Laporan Kendala</h3>
                    <p class="text-muted mb-0">
                        {{ $issueReport->report_number ?? '-' }} — pantau status dan progres penanganan laporan Anda.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('issue-reports.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Ringkasan Laporan --}}
    <div class="page-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="text-muted small mb-1">Nomor Laporan</div>
                <h4 class="fw-bold text-primary mb-2">
                    {{ $issueReport->report_number ?? '-' }}
                </h4>

                <h5 class="fw-semibold mb-2">
                    {{ $issueReport->title ?? 'Laporan Kendala Parkir' }}
                </h5>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $statusBadgeClass($issueReport->status ?? '') }}">
                        {{ $issueReport->status ?? '-' }}
                    </span>

                    <span class="badge rounded-pill {{ $priorityBadgeClass($issueReport->priority ?? '') }}">
                        Prioritas: {{ $issueReport->priority ?? '-' }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        {{ $issueReport->category ?? 'Kategori Tidak Diisi' }}
                    </span>

                    <span class="badge rounded-pill bg-light text-dark border">
                        {{ $totalFollowUp }} Follow Up
                    </span>
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Tanggal Laporan</div>
                <div class="fw-bold">
                    {{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="text-muted small mt-2">Petugas Pelapor</div>
                <div class="fw-bold">
                    {{ $issueReport->reporter->full_name ?? $issueReport->reporter->name ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Konten Kiri --}}
        <div class="col-lg-8">

            {{-- Informasi Laporan --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Informasi Laporan</h5>
                    <p class="text-muted small mb-0">
                        Detail laporan kendala yang telah Anda buat.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Lokasi Parkir</div>
                            <div class="fw-semibold">
                                {{ $issueReport->parkingLocation->location_name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Kode: {{ $issueReport->parkingLocation->location_code ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Area / Zona</div>
                            <div class="fw-semibold">
                                {{ $issueReport->parkingLocation->area ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Titik area kendala parkir
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Kategori Kendala</div>
                            <div class="fw-semibold">
                                {{ $issueReport->category ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Jenis kendala yang dilaporkan
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Prioritas</div>
                            <div class="mt-1">
                                <span class="badge rounded-pill {{ $priorityBadgeClass($issueReport->priority ?? '') }}">
                                    {{ $issueReport->priority ?? '-' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                Tingkat urgensi laporan
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small mb-1">Deskripsi Kendala</div>
                            <div style="white-space: pre-line;">
                                {{ $issueReport->description ?? '-' }}
                            </div>
                        </div>
                    </div>

                    @if ($issueReport->verification_note)
                        <div class="col-md-12">
                            <div class="alert alert-primary mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-check-circle"></i> Catatan Verifikasi
                                </div>
                                {{ $issueReport->verification_note }}
                            </div>
                        </div>
                    @endif

                    @if ($issueReport->rejection_reason)
                        <div class="col-md-12">
                            <div class="alert alert-danger mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-x-circle"></i> Alasan Laporan Ditolak
                                </div>
                                {{ $issueReport->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Foto Bukti --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Foto Bukti Kendala</h5>
                    <p class="text-muted small mb-0">
                        Dokumentasi awal yang Anda lampirkan saat membuat laporan.
                    </p>
                </div>

                @if ($issueReport->photo)
                    <a href="{{ asset('storage/' . $issueReport->photo) }}" target="_blank">
                        <img
                            src="{{ asset('storage/' . $issueReport->photo) }}"
                            alt="Foto Bukti Kendala"
                            class="img-fluid rounded-4 border"
                            style="max-height: 380px; object-fit: cover;"
                        >
                    </a>
                @else
                    <div class="text-center text-muted py-5 border rounded-4">
                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                        Tidak ada foto bukti yang dilampirkan.
                    </div>
                @endif
            </div>

            {{-- Histori --}}
            <div class="page-card p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Histori Laporan</h5>
                    <p class="text-muted small mb-0">
                        Riwayat perubahan status dan aktivitas pada laporan ini.
                    </p>
                </div>

                @forelse ($issueReport->histories as $history)
                    <div class="d-flex gap-3 mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;min-width:42px;">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div class="border rounded-4 p-3 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <div class="fw-bold">
                                        {{ ucwords(str_replace('_', ' ', $history->action ?? 'Aktivitas')) }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $history->created_at?->format('d M Y H:i') ?? '-' }}
                                        —
                                        {{ $history->user->full_name ?? $history->user->name ?? 'Sistem' }}
                                    </div>
                                </div>

                                @if ($history->new_status)
                                    <span class="badge rounded-pill {{ $statusBadgeClass($history->new_status) }}">
                                        {{ $history->new_status }}
                                    </span>
                                @endif
                            </div>

                            @if ($history->notes)
                                <div class="mt-2">
                                    {{ $history->notes }}
                                </div>
                            @endif

                            @if ($history->new_status)
                                <div class="mt-2 small text-muted">
                                    Status:
                                    <span class="fw-semibold">{{ $history->previous_status ?? '-' }}</span>
                                    →
                                    <span class="fw-semibold text-primary">{{ $history->new_status }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5 border rounded-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada histori laporan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Konten Kanan --}}
        <div class="col-lg-4">

            {{-- Informasi Penanganan --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Informasi Penanganan</h5>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th class="text-muted small ps-0">Status</th>
                        <td class="text-end">
                            <span class="badge rounded-pill {{ $statusBadgeClass($issueReport->status ?? '') }}">
                                {{ $issueReport->status ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Teknisi</th>
                        <td class="text-end">
                            {{ $issueReport->assignedTechnician->full_name ?? $issueReport->assignedTechnician->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Diverifikasi Oleh</th>
                        <td class="text-end">
                            {{ $issueReport->verifier->full_name ?? $issueReport->verifier->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Waktu Verifikasi</th>
                        <td class="text-end">
                            {{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Ditutup Pada</th>
                        <td class="text-end">
                            {{ $issueReport->closed_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Progress Penanganan --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Progress Penanganan</h5>

                @if ($issueReport->status === 'Menunggu Verifikasi')
                    <div class="alert alert-warning mb-0">
                        <div class="fw-bold mb-1">Menunggu Verifikasi</div>
                        Laporan Anda sedang menunggu verifikasi dari Manajer Operasional.
                    </div>
                @elseif ($issueReport->status === 'Dalam Proses')
                    <div class="alert alert-info mb-0">
                        <div class="fw-bold mb-1">Sedang Dalam Proses</div>
                        Laporan Anda sudah diverifikasi dan sedang ditangani oleh Teknisi Vendor.
                    </div>
                @elseif ($issueReport->status === 'Menunggu Informasi')
                    <div class="alert alert-primary mb-0">
                        <div class="fw-bold mb-1">Menunggu Informasi</div>
                        Teknisi membutuhkan informasi tambahan sebelum melanjutkan penanganan.
                    </div>
                @elseif ($issueReport->status === 'Selesai Ditangani')
                    <div class="alert alert-success mb-0">
                        <div class="fw-bold mb-1">Selesai Ditangani</div>
                        Kendala sudah selesai ditangani dan menunggu penutupan laporan oleh Manajer.
                    </div>
                @elseif ($issueReport->status === 'Ditutup / Diarsipkan')
                    <div class="alert alert-secondary mb-0">
                        <div class="fw-bold mb-1">Laporan Ditutup</div>
                        Laporan sudah ditutup dan diarsipkan oleh Manajer Operasional.
                    </div>
                @elseif ($issueReport->status === 'Ditolak')
                    <div class="alert alert-danger mb-0">
                        <div class="fw-bold mb-1">Laporan Ditolak</div>
                        Laporan tidak dapat diproses. Silakan lihat alasan penolakan.
                    </div>
                @else
                    <div class="alert alert-secondary mb-0">
                        Status laporan belum dikenali.
                    </div>
                @endif
            </div>

            {{-- Follow Up Teknisi --}}
            <div class="page-card p-4">
                <h5 class="fw-bold mb-1">Follow Up Teknisi</h5>
                <p class="text-muted small mb-3">
                    Update status dan catatan penanganan dari Teknisi Vendor.
                </p>

                @forelse ($issueReport->followUps as $followUp)
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <div>
                                <div class="fw-bold">
                                    {{ $followUp->technician->full_name ?? $followUp->technician->name ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $followUp->created_at?->format('d M Y H:i') ?? '-' }}
                                </div>
                            </div>

                            <span class="badge rounded-pill {{ $statusBadgeClass($followUp->new_status ?? '') }}">
                                {{ $followUp->new_status ?? '-' }}
                            </span>
                        </div>

                        <div class="mb-2 small text-muted">
                            Status:
                            <span class="fw-semibold">
                                {{ $followUp->previous_status ?? '-' }}
                            </span>
                            →
                            <span class="fw-semibold text-primary">
                                {{ $followUp->new_status ?? '-' }}
                            </span>
                        </div>

                        <div class="border rounded-4 p-3 bg-light mb-3">
                            <div class="text-muted small mb-1">Catatan Follow Up</div>
                            <div style="white-space: pre-line;">
                                {{ $followUp->follow_up_note ?? '-' }}
                            </div>
                        </div>

                        @if ($followUp->need_backup_item)
                            <div class="alert alert-warning py-2 mb-3">
                                <div class="fw-bold">
                                    <i class="bi bi-box-seam"></i> Membutuhkan Barang Backup
                                </div>
                                <div class="small">
                                    {{ $followUp->backupItem->item_name ?? '-' }}
                                    sebanyak
                                    {{ $followUp->backup_item_quantity ?? '-' }}
                                    {{ $followUp->backupItem->unit ?? '' }}
                                </div>
                            </div>
                        @endif

                        @if ($followUp->documentation_photo)
                            <a href="{{ asset('storage/' . $followUp->documentation_photo) }}" target="_blank">
                                <img
                                    src="{{ asset('storage/' . $followUp->documentation_photo) }}"
                                    class="img-fluid rounded-3 border"
                                    alt="Dokumentasi Follow Up"
                                >
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-5 border rounded-4">
                        <i class="bi bi-tools fs-1 d-block mb-2"></i>
                        Belum ada follow up teknisi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection