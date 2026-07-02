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
    <div class="title">REKAP LAPORAN KENDALA PARKIR</div>
    <div class="subtitle">ELITE Parkir - Sistem Penanganan Kendala Parkir Berbasis Web</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Laporan</th>
                <th>Petugas</th>
                <th>Lokasi Parkir</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Teknisi Ditugaskan</th>
                <th>Tanggal Laporan</th>
                <th>Deskripsi Kendala</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->report_number ?? $report->report_code ?? '-' }}</td>
                    <td>{{ $report->reporter->name ?? '-' }}</td>
                    <td>{{ $report->parkingLocation->location_name ?? '-' }}</td>
                    <td>{{ $report->priority ?? '-' }}</td>
                    <td>{{ $report->status ?? '-' }}</td>
                    <td>{{ $report->assignedTechnician->name ?? '-' }}</td>
                    <td>{{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $report->description ?? $report->problem_description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">Tidak ada data laporan kendala.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>