@if ($status === 'Menunggu Verifikasi')
    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
@elseif ($status === 'Dalam Proses')
    <span class="badge bg-primary">Dalam Proses</span>
@elseif ($status === 'Menunggu Informasi')
    <span class="badge bg-info text-dark">Menunggu Informasi</span>
@elseif ($status === 'Selesai Ditangani')
    <span class="badge bg-success">Selesai Ditangani</span>
@elseif ($status === 'Ditolak')
    <span class="badge bg-danger">Ditolak</span>
@elseif ($status === 'Ditutup / Diarsipkan')
    <span class="badge bg-secondary">Ditutup / Diarsipkan</span>
@else
    <span class="badge bg-light text-dark">{{ $status }}</span>
@endif