<?php

namespace App\Http\Controllers;

use App\Models\DailyTrafficReport;
use App\Models\Notification;
use App\Models\ParkingLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyTrafficReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat melihat daftar traffic harian.');
        }

        if (!$user->parking_location_id) {
            $trafficReports = DailyTrafficReport::query()
                ->whereRaw('1 = 0')
                ->paginate(10)
                ->withQueryString();

            return view('traffic.index', compact('trafficReports', 'search'))
                ->with('warning', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $trafficReports = DailyTrafficReport::with(['parkingLocation', 'user'])
            ->where('parking_location_id', $user->parking_location_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('report_date', 'like', "%{$search}%")
                        ->orWhere('shift', 'like', "%{$search}%")
                        ->orWhereHas('parkingLocation', function ($locationQuery) use ($search) {
                            $locationQuery->where('location_name', 'like', "%{$search}%")
                                ->orWhere('location_code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('traffic.index', compact('trafficReports', 'search'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat membuat laporan traffic harian.');
        }

        if (!$user->parking_location_id) {
            return redirect()
                ->route('traffic-reports.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $locations = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();

        $selectedLocation = $locations->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('traffic-reports.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        return view('traffic.create', compact('locations', 'selectedLocation'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat menyimpan laporan traffic harian.');
        }

        if (!$user->parking_location_id) {
            return redirect()
                ->route('traffic-reports.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $selectedLocation = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('traffic-reports.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        $validated = $request->validate([
            'report_date' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Malam',
            'total_vehicle_in' => 'required|integer|min:0',
            'total_vehicle_out' => 'required|integer|min:0',
            'car_count' => 'required|integer|min:0',
            'motorcycle_count' => 'required|integer|min:0',
            'other_vehicle_count' => 'required|integer|min:0',
            'total_transaction' => 'required|integer|min:0',
            'total_revenue' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'report_date.required' => 'Tanggal laporan wajib diisi.',
            'report_date.date' => 'Tanggal laporan tidak valid.',
            'shift.required' => 'Shift wajib dipilih.',
            'shift.in' => 'Shift tidak valid.',
            'total_vehicle_in.required' => 'Jumlah kendaraan masuk wajib diisi.',
            'total_vehicle_in.integer' => 'Jumlah kendaraan masuk harus berupa angka.',
            'total_vehicle_in.min' => 'Jumlah kendaraan masuk tidak boleh kurang dari 0.',
            'total_vehicle_out.required' => 'Jumlah kendaraan keluar wajib diisi.',
            'total_vehicle_out.integer' => 'Jumlah kendaraan keluar harus berupa angka.',
            'total_vehicle_out.min' => 'Jumlah kendaraan keluar tidak boleh kurang dari 0.',
            'car_count.required' => 'Jumlah mobil wajib diisi.',
            'car_count.integer' => 'Jumlah mobil harus berupa angka.',
            'car_count.min' => 'Jumlah mobil tidak boleh kurang dari 0.',
            'motorcycle_count.required' => 'Jumlah motor wajib diisi.',
            'motorcycle_count.integer' => 'Jumlah motor harus berupa angka.',
            'motorcycle_count.min' => 'Jumlah motor tidak boleh kurang dari 0.',
            'other_vehicle_count.required' => 'Jumlah kendaraan lain wajib diisi.',
            'other_vehicle_count.integer' => 'Jumlah kendaraan lain harus berupa angka.',
            'other_vehicle_count.min' => 'Jumlah kendaraan lain tidak boleh kurang dari 0.',
            'total_transaction.required' => 'Total transaksi wajib diisi.',
            'total_transaction.integer' => 'Total transaksi harus berupa angka.',
            'total_transaction.min' => 'Total transaksi tidak boleh kurang dari 0.',
            'total_revenue.required' => 'Total pendapatan wajib diisi.',
            'total_revenue.numeric' => 'Total pendapatan harus berupa angka.',
            'total_revenue.min' => 'Total pendapatan tidak boleh kurang dari 0.',
            'photo.image' => 'File dokumentasi harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $exists = DailyTrafficReport::where('parking_location_id', $user->parking_location_id)
            ->whereDate('report_date', $validated['report_date'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'report_date' => 'Laporan traffic untuk lokasi operasional, tanggal, dan shift ini sudah pernah dibuat.',
                ]);
        }

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('daily-traffic-reports', 'public');
        }

        $trafficReport = DailyTrafficReport::create([
            'user_id' => $user->id,
            'parking_location_id' => $user->parking_location_id,
            'report_date' => $validated['report_date'],
            'shift' => $validated['shift'],
            'total_vehicle_in' => $validated['total_vehicle_in'],
            'total_vehicle_out' => $validated['total_vehicle_out'],
            'car_count' => $validated['car_count'],
            'motorcycle_count' => $validated['motorcycle_count'],
            'other_vehicle_count' => $validated['other_vehicle_count'],
            'total_transaction' => $validated['total_transaction'],
            'total_revenue' => $validated['total_revenue'],
            'notes' => $validated['notes'] ?? null,
            'photo' => $photoPath,
        ]);

        $trafficReport->load('parkingLocation');

        $managers = User::where('role', 'manajer')
            ->where('status', 'Aktif')
            ->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Laporan Traffic Harian Baru',
                'message' => 'Petugas Parkir telah menginput laporan traffic harian untuk lokasi ' .
                    ($trafficReport->parkingLocation->location_name ?? 'Lokasi Parkir') .
                    ' pada tanggal ' .
                    $trafficReport->report_date->format('d M Y') .
                    ' shift ' .
                    $trafficReport->shift .
                    '.',
                'type' => 'traffic',
                'reference_id' => $trafficReport->id,
                'reference_type' => 'daily_traffic_reports',
                'url' => route('report-recaps.index'),
            ]);
        }

        return redirect()
            ->route('traffic-reports.index')
            ->with('success', 'Laporan traffic harian berhasil disimpan dan notifikasi dikirim ke Manajer Operasional.');
    }

    public function show(DailyTrafficReport $trafficReport)
    {
        $user = Auth::user();

        if ($user->role === 'petugas') {
            if (!$user->parking_location_id || $trafficReport->parking_location_id !== $user->parking_location_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan traffic ini.');
            }
        } elseif ($user->role !== 'manajer') {
            abort(403, 'Anda tidak memiliki akses ke laporan traffic ini.');
        }

        $trafficReport->load(['parkingLocation', 'user']);

        return view('traffic.show', compact('trafficReport'));
    }

    public function edit(DailyTrafficReport $trafficReport)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengubah laporan traffic.');
        }

        if ($trafficReport->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
        }

        if (!$user->parking_location_id || $trafficReport->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
        }

        $trafficReport->load(['parkingLocation', 'user']);

        $locations = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();

        $selectedLocation = $locations->first();

        return view('traffic.edit', compact('trafficReport', 'locations', 'selectedLocation'));
    }

    public function update(Request $request, DailyTrafficReport $trafficReport)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengubah laporan traffic.');
        }

        if ($trafficReport->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
        }

        if (!$user->parking_location_id || $trafficReport->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
        }

        $selectedLocation = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('traffic-reports.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        $validated = $request->validate([
            'report_date' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Malam',
            'total_vehicle_in' => 'required|integer|min:0',
            'total_vehicle_out' => 'required|integer|min:0',
            'car_count' => 'required|integer|min:0',
            'motorcycle_count' => 'required|integer|min:0',
            'other_vehicle_count' => 'required|integer|min:0',
            'total_transaction' => 'required|integer|min:0',
            'total_revenue' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $exists = DailyTrafficReport::where('parking_location_id', $user->parking_location_id)
            ->whereDate('report_date', $validated['report_date'])
            ->where('shift', $validated['shift'])
            ->where('id', '!=', $trafficReport->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'report_date' => 'Laporan traffic untuk lokasi operasional, tanggal, dan shift ini sudah pernah dibuat.',
                ]);
        }

        $photoPath = $trafficReport->photo;

        if ($request->hasFile('photo')) {
            if ($trafficReport->photo && Storage::disk('public')->exists($trafficReport->photo)) {
                Storage::disk('public')->delete($trafficReport->photo);
            }

            $photoPath = $request->file('photo')->store('daily-traffic-reports', 'public');
        }

        $trafficReport->update([
            'parking_location_id' => $user->parking_location_id,
            'report_date' => $validated['report_date'],
            'shift' => $validated['shift'],
            'total_vehicle_in' => $validated['total_vehicle_in'],
            'total_vehicle_out' => $validated['total_vehicle_out'],
            'car_count' => $validated['car_count'],
            'motorcycle_count' => $validated['motorcycle_count'],
            'other_vehicle_count' => $validated['other_vehicle_count'],
            'total_transaction' => $validated['total_transaction'],
            'total_revenue' => $validated['total_revenue'],
            'notes' => $validated['notes'] ?? null,
            'photo' => $photoPath,
        ]);

        return redirect()
            ->route('traffic-reports.show', $trafficReport)
            ->with('success', 'Laporan traffic harian berhasil diperbarui.');
    }

    public function destroy(DailyTrafficReport $trafficReport)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat menghapus laporan traffic.');
        }

        if ($trafficReport->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        if (!$user->parking_location_id || $trafficReport->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        if ($trafficReport->photo && Storage::disk('public')->exists($trafficReport->photo)) {
            Storage::disk('public')->delete($trafficReport->photo);
        }

        $trafficReport->delete();

        return redirect()
            ->route('traffic-reports.index')
            ->with('success', 'Laporan traffic harian berhasil dihapus.');
    }
}