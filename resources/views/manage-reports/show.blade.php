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

    $canVerifyAssign = $issueReport->status === 'Menunggu Verifikasi';
    $canReject = in_array($issueReport->status, ['Menunggu Verifikasi', 'Dalam Proses', 'Menunggu Informasi'], true);
    $canClose = $issueReport->status === 'Selesai Ditangani';

    $reporterName = $issueReport->reporter->full_name
        ?? $issueReport->reporter->name
        ?? '-';

    $technicianName = $issueReport->assignedTechnician->full_name
        ?? $issueReport->assignedTechnician->name
        ?? 'Belum ditugaskan';

    $verifierName = $issueReport->verifier->full_name
        ?? $issueReport->verifier->name
        ?? 'Belum diverifikasi';
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-clipboard-data fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Detail Laporan Kendala</h3>
                    <p class="text-muted mb-0">
                        {{ $issueReport->report_number ?? '-' }} — Monitoring laporan, verifikasi, penugasan teknisi, dan histori penanganan.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('manage-reports.index') }}" class="btn btn-outline-secondary rounded-3">
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
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Tanggal Laporan</div>
                <div class="fw-bold">
                    {{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                @if ($issueReport->closed_at)
                    <div class="text-muted small mt-2">Tanggal Ditutup</div>
                    <div class="fw-bold">
                        {{ $issueReport->closed_at?->format('d M Y H:i') ?? '-' }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress --}}
    <div class="page-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Progress Penanganan</h5>

        <div class="row g-3">
            <div class="col-md">
                <div class="border rounded-4 p-3 h-100 {{ $issueReport->status ? 'bg-light' : '' }}">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-send-check text-primary me-1"></i>
                        1. Laporan Masuk
                    </div>
                    <div class="text-muted small">
                        Petugas membuat laporan kendala.
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="border rounded-4 p-3 h-100 {{ in_array($issueReport->status, ['Dalam Proses', 'Menunggu Informasi', 'Selesai Ditangani', 'Ditutup / Diarsipkan'], true) ? 'bg-light' : '' }}">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-person-check text-primary me-1"></i>
                        2. Verifikasi
                    </div>
                    <div class="text-muted small">
                        Manajer verifikasi dan assign teknisi.
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="border rounded-4 p-3 h-100 {{ in_array($issueReport->status, ['Dalam Proses', 'Menunggu Informasi', 'Selesai Ditangani', 'Ditutup / Diarsipkan'], true) ? 'bg-light' : '' }}">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-tools text-primary me-1"></i>
                        3. Penanganan
                    </div>
                    <div class="text-muted small">
                        Teknisi melakukan follow up.
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="border rounded-4 p-3 h-100 {{ in_array($issueReport->status, ['Ditutup / Diarsipkan'], true) ? 'bg-light' : '' }}">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-archive text-primary me-1"></i>
                        4. Closing
                    </div>
                    <div class="text-muted small">
                        Manajer menutup laporan.
                    </div>
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
                        Data utama laporan kendala yang dibuat oleh Petugas Parkir.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Petugas Pelapor</div>
                            <div class="fw-semibold">
                                {{ $reporterName }}
                            </div>
                            <div class="text-muted small">
                                NIK: {{ $issueReport->reporter->username ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Lokasi Parkir</div>
                            <div class="fw-semibold">
                                {{ $issueReport->parkingLocation->location_name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                Kode Lokasi: {{ $issueReport->parkingLocation->location_code ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Teknisi Ditugaskan</div>
                            <div class="fw-semibold">
                                {{ $technicianName }}
                            </div>
                            <div class="text-muted small">
                                NIK: {{ $issueReport->assignedTechnician->username ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small">Diverifikasi Oleh</div>
                            <div class="fw-semibold">
                                {{ $verifierName }}
                            </div>
                            <div class="text-muted small">
                                {{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small mb-1">Deskripsi Kendala</div>
                            <div style="white-space: pre-line;">{{ $issueReport->description ?? '-' }}</div>
                        </div>
                    </div>

                    @if ($issueReport->verification_note)
                        <div class="col-md-12">
                            <div class="alert alert-primary mb-0 rounded-4">
                                <b>Catatan Verifikasi:</b><br>
                                {{ $issueReport->verification_note }}
                            </div>
                        </div>
                    @endif

                    @if ($issueReport->rejection_reason)
                        <div class="col-md-12">
                            <div class="alert alert-danger mb-0 rounded-4">
                                <b>Alasan Ditolak:</b><br>
                                {{ $issueReport->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Foto Bukti --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Dokumentasi Kendala</h5>
                    <p class="text-muted small mb-0">
                        Foto bukti kendala yang dilampirkan oleh Petugas.
                    </p>
                </div>

                @if ($issueReport->photo)
                    <a href="{{ asset('storage/' . $issueReport->photo) }}" target="_blank">
                        <img
                            src="{{ asset('storage/' . $issueReport->photo) }}"
                            alt="Foto Bukti Kendala"
                            class="img-fluid rounded-4 border"
                            style="max-height: 420px; object-fit: cover;"
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
                                <div class="mt-2">{{ $history->notes }}</div>
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

            {{-- Panel Aksi --}}
            @if ($canVerifyAssign)
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-1">Verifikasi & Tugaskan Teknisi</h5>
                    <p class="text-muted small mb-3">
                        Pilih Teknisi Vendor untuk menangani laporan kendala ini.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.verify-assign', $issueReport) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Teknisi Vendor <span class="text-danger">*</span></label>
                            <select name="assigned_technician_id" class="form-select @error('assigned_technician_id') is-invalid @enderror">
                                <option value="">Pilih Teknisi Vendor</option>
                                @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ old('assigned_technician_id') == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->full_name ?? $technician->name }} - {{ $technician->username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_technician_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Verifikasi</label>
                            <textarea name="verification_note" rows="4" class="form-control" placeholder="Masukkan catatan verifikasi jika diperlukan...">{{ old('verification_note') }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Verifikasi & Tugaskan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canReject)
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-1 text-danger">Tolak Laporan</h5>
                    <p class="text-muted small mb-3">
                        Gunakan aksi ini jika laporan tidak valid atau tidak dapat dilanjutkan.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.reject', $issueReport) }}" onsubmit="return confirm('Yakin ingin menolak laporan ini?')">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" rows="4" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Masukkan alasan penolakan...">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-danger w-100 rounded-3">
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak Laporan
                        </button>
                    </form>
                </div>
            @endif

            @if ($canClose)
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-1">Tutup Laporan</h5>
                    <p class="text-muted small mb-3">
                        Laporan sudah selesai ditangani oleh Teknisi Vendor. Manajer dapat menutup dan mengarsipkan laporan ini.
                    </p>

                    <form method="POST" action="{{ route('manage-reports.close', $issueReport) }}" onsubmit="return confirm('Yakin ingin menutup laporan ini?')">
                        @csrf

                        <button class="btn btn-success w-100 rounded-3">
                            <i class="bi bi-archive me-1"></i>
                            Tutup / Arsipkan Laporan
                        </button>
                    </form>
                </div>
            @endif

            @if (!$canVerifyAssign && !$canReject && !$canClose)
                <div class="page-card p-4 mb-4">
                    <h5 class="fw-bold mb-1">Aksi Laporan</h5>
                    <p class="text-muted small mb-0">
                        Tidak ada aksi yang tersedia untuk status laporan saat ini.
                    </p>
                </div>
            @endif

            {{-- Informasi Penanganan --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Informasi Penanganan</h5>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th class="text-muted small ps-0">Petugas</th>
                        <td class="text-end">{{ $reporterName }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Teknisi</th>
                        <td class="text-end">{{ $technicianName }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Diverifikasi</th>
                        <td class="text-end">{{ $verifierName }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Waktu Verifikasi</th>
                        <td class="text-end">{{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Ditutup Pada</th>
                        <td class="text-end">{{ $issueReport->closed_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            {{-- Follow Up Teknisi --}}
            <div class="page-card p-4">
                <h5 class="fw-bold mb-1">Follow Up Teknisi</h5>
                <p class="text-muted small mb-3">
                    Catatan dan dokumentasi penanganan dari Teknisi Vendor.
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

                            @if ($followUp->new_status)
                                <span class="badge rounded-pill {{ $statusBadgeClass($followUp->new_status) }}">
                                    {{ $followUp->new_status }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-2 small text-muted">
                            Status:
                            <span class="fw-semibold">{{ $followUp->previous_status ?? '-' }}</span>
                            →
                            <span class="fw-semibold text-primary">{{ $followUp->new_status ?? '-' }}</span>
                        </div>

                        <div style="white-space: pre-line;">
                            {{ $followUp->follow_up_note ?? '-' }}
                        </div>

                        @if ($followUp->need_backup_item)
                            <div class="alert alert-warning rounded-4 mt-3 mb-0">
                                <div class="fw-bold mb-1">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Membutuhkan Barang Backup
                                </div>
                                <div class="small">
                                    Barang: {{ $followUp->backupItem->item_name ?? '-' }}<br>
                                    Jumlah: {{ $followUp->backup_item_quantity ?? 0 }}<br>
                                    Catatan: {{ $followUp->backup_item_note ?? '-' }}
                                </div>
                            </div>
                        @endif

                        @if ($followUp->documentation_photo)
                            <a href="{{ asset('storage/' . $followUp->documentation_photo) }}" target="_blank">
                                <img
                                    src="{{ asset('storage/' . $followUp->documentation_photo) }}"
                                    class="img-fluid rounded-3 border mt-3"
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