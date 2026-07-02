@extends('layouts.app')

@section('title', 'Update Status Laporan | Sistem Penanganan Kendala Parkir')

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

    $reporterName = $issueReport->reporter->full_name
        ?? $issueReport->reporter->name
        ?? '-';

    $technicianName = $issueReport->assignedTechnician->full_name
        ?? $issueReport->assignedTechnician->name
        ?? '-';

    $verifierName = $issueReport->verifier->full_name
        ?? $issueReport->verifier->name
        ?? '-';

    $canUpdate = !in_array($issueReport->status, ['Ditolak', 'Ditutup / Diarsipkan'], true);
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary text-white"
                     style="width: 56px; height: 56px;">
                    <i class="bi bi-tools fs-3"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-1">Update Status Laporan</h3>
                    <p class="text-muted mb-0">
                        {{ $issueReport->report_number ?? '-' }} — Update progress penanganan kendala parkir.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('technician-reports.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Ringkasan --}}
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

                    <span class="badge rounded-pill bg-primary">
                        Follow Up: {{ $totalFollowUp }}
                    </span>
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Tanggal Laporan</div>
                <div class="fw-bold">
                    {{ $issueReport->created_at?->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="text-muted small mt-2">Diverifikasi</div>
                <div class="fw-bold">
                    {{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Progress --}}
    <div class="page-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Progress Penanganan</h5>

        <div class="row g-3">
            <div class="col-md">
                <div class="border rounded-4 p-3 h-100 bg-light">
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
                <div class="border rounded-4 p-3 h-100 bg-light">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-person-check text-primary me-1"></i>
                        2. Ditugaskan
                    </div>
                    <div class="text-muted small">
                        Manajer menugaskan laporan kepada teknisi.
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
                <div class="border rounded-4 p-3 h-100 {{ in_array($issueReport->status, ['Selesai Ditangani', 'Ditutup / Diarsipkan'], true) ? 'bg-light' : '' }}">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-check-circle text-primary me-1"></i>
                        4. Selesai
                    </div>
                    <div class="text-muted small">
                        Teknisi menyelesaikan penanganan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kiri --}}
        <div class="col-lg-8">

            {{-- Informasi Laporan --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Informasi Laporan</h5>
                    <p class="text-muted small mb-0">
                        Detail laporan kendala yang harus ditangani.
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
                                <b>Catatan Verifikasi Manajer:</b><br>
                                {{ $issueReport->verification_note }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dokumentasi Petugas --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Dokumentasi Kendala</h5>
                    <p class="text-muted small mb-0">
                        Foto bukti awal dari Petugas Parkir.
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

            {{-- Riwayat Follow Up --}}
            <div class="page-card p-4 mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Riwayat Follow Up</h5>
                    <p class="text-muted small mb-0">
                        Catatan update penanganan yang sudah Anda input.
                    </p>
                </div>

                @forelse ($issueReport->followUps as $followUp)
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
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

                        <div class="small text-muted mb-2">
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
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada follow up penanganan.
                    </div>
                @endforelse
            </div>

            {{-- Histori --}}
            <div class="page-card p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Histori Laporan</h5>
                    <p class="text-muted small mb-0">
                        Riwayat perubahan status dan aktivitas laporan.
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

        {{-- Kanan --}}
        <div class="col-lg-4">

            {{-- Form Update Status --}}
            <div class="page-card p-4 mb-4">
                <h5 class="fw-bold mb-1">Update Status Penanganan</h5>
                <p class="text-muted small mb-3">
                    Input hasil penanganan dan update status laporan.
                </p>

                @if ($canUpdate)
                    <form method="POST"
                          action="{{ route('technician-reports.update-status', $issueReport) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                            <select name="new_status" class="form-select @error('new_status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="Dalam Proses" {{ old('new_status', $issueReport->status) === 'Dalam Proses' ? 'selected' : '' }}>
                                    Dalam Proses
                                </option>
                                <option value="Menunggu Informasi" {{ old('new_status') === 'Menunggu Informasi' ? 'selected' : '' }}>
                                    Menunggu Informasi
                                </option>
                                <option value="Selesai Ditangani" {{ old('new_status') === 'Selesai Ditangani' ? 'selected' : '' }}>
                                    Selesai Ditangani
                                </option>
                            </select>
                            @error('new_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Penanganan <span class="text-danger">*</span></label>
                            <textarea
                                name="follow_up_note"
                                rows="5"
                                class="form-control @error('follow_up_note') is-invalid @enderror"
                                placeholder="Contoh: Kendala telah dicek, dilakukan penggantian komponen, sistem kembali normal..."
                            >{{ old('follow_up_note') }}</textarea>
                            @error('follow_up_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumentasi Penanganan</label>
                            <input
                                type="file"
                                name="documentation_photo"
                                class="form-control @error('documentation_photo') is-invalid @enderror"
                                accept="image/*"
                            >
                            <div class="form-text">Format JPG, JPEG, PNG. Maksimal 2 MB.</div>
                            @error('documentation_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="border rounded-4 p-3 mb-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="need_backup_item"
                                    name="need_backup_item"
                                    {{ old('need_backup_item') ? 'checked' : '' }}
                                >
                                <label class="form-check-label fw-semibold" for="need_backup_item">
                                    Membutuhkan Barang Backup
                                </label>
                            </div>

                            <div class="text-muted small mt-1">
                                Centang jika penanganan membutuhkan barang backup dari stok operasional.
                            </div>

                            <div id="backupItemSection" class="mt-3" style="{{ old('need_backup_item') ? '' : 'display:none;' }}">
                                <div class="mb-3">
                                    <label class="form-label">Barang Backup</label>
                                    <select name="backup_item_id" class="form-select @error('backup_item_id') is-invalid @enderror">
                                        <option value="">Pilih Barang Backup</option>
                                        @foreach ($backupItems as $item)
                                            <option value="{{ $item->id }}" {{ old('backup_item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->item_name }} — Stok: {{ $item->stock }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('backup_item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jumlah</label>
                                    <input
                                        type="number"
                                        name="backup_item_quantity"
                                        value="{{ old('backup_item_quantity') }}"
                                        min="1"
                                        class="form-control @error('backup_item_quantity') is-invalid @enderror"
                                        placeholder="Masukkan jumlah barang"
                                    >
                                    @error('backup_item_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Catatan Barang Backup</label>
                                    <textarea
                                        name="backup_item_note"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Catatan kebutuhan barang backup..."
                                    >{{ old('backup_item_note') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-save me-1"></i>
                            Simpan Update Status
                        </button>
                    </form>
                @else
                    <div class="alert alert-secondary rounded-4 mb-0">
                        Laporan dengan status <b>{{ $issueReport->status }}</b> sudah tidak dapat diperbarui.
                    </div>
                @endif
            </div>

            {{-- Informasi Singkat --}}
            <div class="page-card p-4">
                <h5 class="fw-bold mb-3">Informasi Penugasan</h5>

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
                        <th class="text-muted small ps-0">Manajer Verifikasi</th>
                        <td class="text-end">{{ $verifierName }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Waktu Verifikasi</th>
                        <td class="text-end">{{ $issueReport->verified_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small ps-0">Total Follow Up</th>
                        <td class="text-end">{{ $totalFollowUp }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const needBackupItem = document.getElementById('need_backup_item');
    const backupItemSection = document.getElementById('backupItemSection');

    if (needBackupItem && backupItemSection) {
        needBackupItem.addEventListener('change', function () {
            backupItemSection.style.display = this.checked ? '' : 'none';
        });
    }
</script>
@endsection