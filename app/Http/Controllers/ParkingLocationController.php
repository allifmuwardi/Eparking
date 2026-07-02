<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use Illuminate\Http\Request;

class ParkingLocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $locations = ParkingLocation::query()
            ->when($search, function ($query) use ($search) {
                $query->where('location_code', 'like', "%{$search}%")
                    ->orWhere('location_name', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('pic_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.parking-locations.index', compact('locations', 'search'));
    }

    public function create()
    {
        return view('master.parking-locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_code' => 'required|string|max:50|unique:parking_locations,location_code',
            'location_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'area' => 'nullable|string|max:255',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:30',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'location_code.required' => 'Kode lokasi wajib diisi.',
            'location_code.unique' => 'Kode lokasi sudah digunakan.',
            'location_name.required' => 'Nama lokasi wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        ParkingLocation::create($validated);

        return redirect()
            ->route('parking-locations.index')
            ->with('success', 'Data lokasi parkir berhasil ditambahkan.');
    }

    public function show(ParkingLocation $parkingLocation)
    {
        return view('master.parking-locations.show', compact('parkingLocation'));
    }

    public function edit(ParkingLocation $parkingLocation)
    {
        return view('master.parking-locations.edit', compact('parkingLocation'));
    }

    public function update(Request $request, ParkingLocation $parkingLocation)
    {
        $validated = $request->validate([
            'location_code' => 'required|string|max:50|unique:parking_locations,location_code,' . $parkingLocation->id,
            'location_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'area' => 'nullable|string|max:255',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:30',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'location_code.required' => 'Kode lokasi wajib diisi.',
            'location_code.unique' => 'Kode lokasi sudah digunakan.',
            'location_name.required' => 'Nama lokasi wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $parkingLocation->update($validated);

        return redirect()
            ->route('parking-locations.index')
            ->with('success', 'Data lokasi parkir berhasil diperbarui.');
    }

    public function destroy(ParkingLocation $parkingLocation)
    {
        $parkingLocation->delete();

        return redirect()
            ->route('parking-locations.index')
            ->with('success', 'Data lokasi parkir berhasil dihapus.');
    }
}