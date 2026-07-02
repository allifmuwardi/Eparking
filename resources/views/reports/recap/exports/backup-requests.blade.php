<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th {
            background-color: #0d6efd;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            border: 1px solid #000000;
            padding: 8px;
        }

        td {
            border: 1px solid #000000;
            padding: 6px;
            vertical-align: top;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="title">REKAP PERMINTAAN BARANG BACKUP</div>
    <div class="subtitle">ELITE Parkir - Sistem Penanganan Kendala Parkir Berbasis Web</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pemohon</th>
                <th>Lokasi Parkir</th>
                <th>Barang Backup</th>
                <th>Quantity</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Diverifikasi Oleh</th>
                <th>Diproses Oleh</th>
                <th>Tanggal Permintaan</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $index => $backup)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $backup->requester->name ?? '-' }}</td>
                    <td>{{ $backup->parkingLocation->location_name ?? '-' }}</td>
                    <td>{{ $backup->backupItem->item_name ?? '-' }}</td>
                    <td>{{ $backup->quantity ?? 0 }}</td>
                    <td>{{ $backup->priority ?? '-' }}</td>
                    <td>{{ $backup->status ?? '-' }}</td>
                    <td>{{ $backup->verifier->name ?? '-' }}</td>
                    <td>{{ $backup->processor->name ?? '-' }}</td>
                    <td>{{ $backup->created_at ? $backup->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $backup->reason ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">Tidak ada data permintaan backup.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>