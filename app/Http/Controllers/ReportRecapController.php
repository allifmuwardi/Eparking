<?php

namespace App\Http\Controllers;

use App\Models\BackupRequest;
use App\Models\DailyTrafficReport;
use App\Models\IssueReport;
use App\Models\ParkingLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportRecapController extends Controller
{
    public function index(Request $request)
    {
        $locations = ParkingLocation::orderBy('location_name')->get();

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $locationId = $request->parking_location_id;
        $status = $request->status;

        $issueReportQuery = IssueReport::with(['reporter', 'parkingLocation']);
        $issueReportQuery = $this->applyIssueReportFilters($issueReportQuery, $request);

        $trafficReportQuery = DailyTrafficReport::with(['user', 'parkingLocation']);
        $trafficReportQuery = $this->applyTrafficReportFilters($trafficReportQuery, $request);

        $backupRequestQuery = BackupRequest::with(['requester', 'parkingLocation', 'backupItem']);
        $backupRequestQuery = $this->applyBackupRequestFilters($backupRequestQuery, $request);

        $totalIssueReports = (clone $issueReportQuery)->count();
        $totalTrafficReports = (clone $trafficReportQuery)->count();
        $totalBackupRequests = (clone $backupRequestQuery)->count();

        $issueReports = $issueReportQuery
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        $trafficReports = $trafficReportQuery
            ->orderBy('report_date', 'asc')
            ->limit(10)
            ->get();

        $backupRequests = $backupRequestQuery
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        $issueStatuses = [
            'Menunggu Verifikasi',
            'Dalam Proses',
            'Menunggu Informasi',
            'Selesai Ditangani',
            'Ditolak',
            'Ditutup / Diarsipkan',
        ];

        $backupStatuses = [
            'Menunggu Verifikasi',
            'Disetujui',
            'Ditolak',
            'Dalam Proses',
            'Selesai',
        ];

        return view('reports.recap.index', compact(
            'locations',
            'startDate',
            'endDate',
            'locationId',
            'status',
            'issueStatuses',
            'backupStatuses',
            'totalIssueReports',
            'totalTrafficReports',
            'totalBackupRequests',
            'issueReports',
            'trafficReports',
            'backupRequests'
        ));
    }

    public function exportIssueReports(Request $request)
    {
        $fileName = 'rekap-laporan-kendala-' . now()->format('Ymd-His') . '.xls';

        $query = IssueReport::with(['reporter', 'parkingLocation', 'assignedTechnician']);

        $reports = $this->applyIssueReportFilters($query, $request)
            ->latest()
            ->get();

        $html = view('reports.recap.exports.issue-reports', compact('reports'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function exportTrafficReports(Request $request)
    {
        $fileName = 'rekap-traffic-harian-' . now()->format('Ymd-His') . '.xls';

        $query = DailyTrafficReport::with(['user', 'parkingLocation']);

        $reports = $this->applyTrafficReportFilters($query, $request)
            ->orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $html = $this->buildTrafficReportExportHtml($reports);

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function exportBackupRequests(Request $request)
    {
        $fileName = 'rekap-permintaan-backup-' . now()->format('Ymd-His') . '.xls';

        $query = BackupRequest::with(['requester', 'parkingLocation', 'backupItem', 'verifier', 'processor']);

        $reports = $this->applyBackupRequestFilters($query, $request)
            ->latest()
            ->get();

        $html = view('reports.recap.exports.backup-requests', compact('reports'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    private function applyIssueReportFilters($query, Request $request)
    {
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->parking_location_id) {
            $query->where('parking_location_id', $request->parking_location_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    private function applyTrafficReportFilters($query, Request $request)
    {
        if ($request->start_date) {
            $query->whereDate('report_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('report_date', '<=', $request->end_date);
        }

        if ($request->parking_location_id) {
            $query->where('parking_location_id', $request->parking_location_id);
        }

        return $query;
    }

    private function applyBackupRequestFilters($query, Request $request)
    {
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->parking_location_id) {
            $query->where('parking_location_id', $request->parking_location_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    private function buildTrafficReportExportHtml($reports): string
    {
        $totalVehicleIn = $reports->sum('total_vehicle_in');
        $totalVehicleOut = $reports->sum('total_vehicle_out');
        $totalCar = $reports->sum('car_count');
        $totalMotorcycle = $reports->sum('motorcycle_count');
        $totalOtherVehicle = $reports->sum('other_vehicle_count');
        $totalTransaction = $reports->sum('total_transaction');
        $totalRevenue = $reports->sum('total_revenue');

        $html = '
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
                    background: #0d6efd;
                    color: #ffffff;
                    font-weight: bold;
                    border: 1px solid #000000;
                    padding: 8px;
                    text-align: center;
                }

                td {
                    border: 1px solid #000000;
                    padding: 7px;
                    vertical-align: top;
                }

                .title {
                    font-size: 18px;
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 4px;
                }

                .subtitle {
                    font-size: 12px;
                    text-align: center;
                    margin-bottom: 16px;
                }

                .text-center {
                    text-align: center;
                }

                .text-right {
                    text-align: right;
                }

                .total-row {
                    background: #eaf3ff;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="title">REKAP TRAFFIC HARIAN PARKIR</div>
            <div class="subtitle">Dicetak pada ' . e(now()->format('d M Y H:i')) . '</div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Kode Lokasi</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
                        <th>NIK Petugas</th>
                        <th>Kendaraan Masuk</th>
                        <th>Kendaraan Keluar</th>
                        <th>Mobil</th>
                        <th>Motor</th>
                        <th>Kendaraan Lain</th>
                        <th>Total Transaksi</th>
                        <th>Total Pendapatan</th>
                        <th>Catatan</th>
                        <th>Waktu Input</th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($reports as $index => $report) {
            $locationName = $report->parkingLocation->location_name ?? '-';
            $locationCode = $report->parkingLocation->location_code ?? '-';

            $petugasName = $report->user->full_name
                ?? $report->user->name
                ?? '-';

            $petugasNik = $report->user->username ?? '-';

            $html .= '
                <tr>
                    <td class="text-center">' . ($index + 1) . '</td>
                    <td class="text-center">' . e($this->formatDate($report->report_date)) . '</td>
                    <td class="text-center">' . e($report->shift ?? '-') . '</td>
                    <td>' . e($locationCode) . '</td>
                    <td>' . e($locationName) . '</td>
                    <td>' . e($petugasName) . '</td>
                    <td>' . e($petugasNik) . '</td>
                    <td class="text-right">' . e((string) ($report->total_vehicle_in ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->total_vehicle_out ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->car_count ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->motorcycle_count ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->other_vehicle_count ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->total_transaction ?? 0)) . '</td>
                    <td class="text-right">' . e((string) ($report->total_revenue ?? 0)) . '</td>
                    <td>' . e($report->notes ?? '-') . '</td>
                    <td class="text-center">' . e($this->formatDateTime($report->created_at)) . '</td>
                </tr>
            ';
        }

        $html .= '
                <tr class="total-row">
                    <td colspan="7" class="text-center">TOTAL</td>
                    <td class="text-right">' . e((string) $totalVehicleIn) . '</td>
                    <td class="text-right">' . e((string) $totalVehicleOut) . '</td>
                    <td class="text-right">' . e((string) $totalCar) . '</td>
                    <td class="text-right">' . e((string) $totalMotorcycle) . '</td>
                    <td class="text-right">' . e((string) $totalOtherVehicle) . '</td>
                    <td class="text-right">' . e((string) $totalTransaction) . '</td>
                    <td class="text-right">' . e((string) $totalRevenue) . '</td>
                    <td colspan="2"></td>
                </tr>
                </tbody>
            </table>
        </body>
        </html>
        ';

        return $html;
    }

    private function formatDate($value): string
    {
        if (!$value) {
            return '-';
        }

        try {
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Exception $e) {
            return '-';
        }
    }

    private function formatDateTime($value): string
    {
        if (!$value) {
            return '-';
        }

        try {
            return Carbon::parse($value)->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return '-';
        }
    }
}