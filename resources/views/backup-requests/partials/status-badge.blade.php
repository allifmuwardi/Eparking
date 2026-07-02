@if ($status === 'Menunggu Verifikasi')
    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
@elseif ($status === 'Disetujui')
    <span class="badge bg-primary">Disetujui</span>
@elseif ($status === 'Ditolak')
    <span class="badge bg-danger">Ditolak</span>
@elseif ($status === 'Dalam Proses')
    <span class="badge bg-info text-dark">Dalam Proses</span>
@elseif ($status === 'Selesai')
    <span class="badge bg-success">Selesai</span>
@else
    <span class="badge bg-light text-dark">{{ $status }}</span>
@endif