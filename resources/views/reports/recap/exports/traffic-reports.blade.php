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
    <div class="title">REKAP TRAFFIC HARIAN PARKIR</div>
    <div class="subtitle">ELITE Parkir - Sistem Penanganan Kendala Parkir Berbasis Web</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Petugas</th>
                <th>Lokasi Parkir</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Kendaraan Masuk</th>
                <th>Kendaraan Keluar</th>
                <th>Mobil</th>
                <th>Motor</th>
                <th>Kendaraan Lain</th>
                <th>Total Transaksi</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $index => $traffic)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $traffic->user->name ?? '-' }}</td>
                    <td>{{ $traffic->parkingLocation->location_name ?? '-' }}</td>
                    <td>{{ $traffic->report_date ? \Carbon\Carbon::parse($traffic->report_date)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $traffic->shift ?? '-' }}</td>
                    <td>{{ $traffic->vehicle_in ?? $traffic->vehicles_in ?? 0 }}</td>
                    <td>{{ $traffic->vehicle_out ?? $traffic->vehicles_out ?? 0 }}</td>
                    <td>{{ $traffic->car_count ?? $traffic->total_car ?? 0 }}</td>
                    <td>{{ $traffic->motorcycle_count ?? $traffic->motor_count ?? $traffic->total_motorcycle ?? 0 }}</td>
                    <td>{{ $traffic->other_vehicle_count ?? $traffic->other_count ?? 0 }}</td>
                    <td>{{ $traffic->total_transaction ?? $traffic->transactions_total ?? 0 }}</td>
                    <td>Rp {{ number_format($traffic->income ?? $traffic->revenue ?? $traffic->total_income ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align:center;">Tidak ada data traffic harian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>