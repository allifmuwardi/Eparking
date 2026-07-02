<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();

        if (!in_array($authUser->role, ['admin', 'manajer'], true)) {
            abort(403, 'Anda tidak memiliki akses ke data pengguna.');
        }

        $search = $request->search;
        $role = $request->role;
        $status = $request->status;
        $parkingLocationId = $request->parking_location_id;

        $users = User::query()
            ->with('parkingLocation')
            ->whereIn('role', ['petugas', 'teknisi'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('username', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('parkingLocation', function ($locationQuery) use ($search) {
                            $locationQuery->where('location_name', 'like', "%{$search}%")
                                ->orWhere('location_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($parkingLocationId, function ($query) use ($parkingLocationId) {
                $query->where('parking_location_id', $parkingLocationId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => User::whereIn('role', ['petugas', 'teknisi'])->count(),
            'active' => User::whereIn('role', ['petugas', 'teknisi'])->where('status', 'Aktif')->count(),
            'inactive' => User::whereIn('role', ['petugas', 'teknisi'])->where('status', 'Tidak Aktif')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'teknisi' => User::where('role', 'teknisi')->count(),
        ];

        $parkingLocations = $this->getActiveParkingLocations();

        return view('user-management.index', compact(
            'users',
            'search',
            'role',
            'status',
            'parkingLocationId',
            'summary',
            'parkingLocations'
        ));
    }

    public function create()
    {
        $this->ensureAdminOperational();

        $parkingLocations = $this->getActiveParkingLocations();

        return view('user-management.create', compact('parkingLocations'));
    }

    public function store(Request $request)
    {
        $this->ensureAdminOperational();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
            ],
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'role' => [
                'required',
                Rule::in(['petugas', 'teknisi']),
            ],
            'parking_location_id' => [
                'required',
                'integer',
                'exists:parking_locations,id',
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Tidak Aktif']),
            ],
        ], [
            'username.required' => 'NIK wajib diisi.',
            'username.unique' => 'NIK sudah digunakan oleh akun lain.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role hanya boleh Petugas Parkir atau Teknisi Vendor.',
            'parking_location_id.required' => 'Lokasi operasional wajib dipilih.',
            'parking_location_id.exists' => 'Lokasi operasional tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $initialPassword = $this->generateInitialPassword();

        $user = User::create([
            'username' => $validated['username'],
            'nip' => $validated['username'],
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'parking_location_id' => $validated['parking_location_id'],
            'status' => $validated['status'],
            'password' => Hash::make($initialPassword),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', 'Akun pengguna berhasil dibuat.')
            ->with('initial_password', $initialPassword);
    }

    public function show(User $user)
    {
        $authUser = Auth::user();

        if (!in_array($authUser->role, ['admin', 'manajer'], true)) {
            abort(403, 'Anda tidak memiliki akses ke detail pengguna.');
        }

        if (!in_array($user->role, ['petugas', 'teknisi'], true)) {
            abort(403, 'Data pengguna ini tidak termasuk pengguna operasional.');
        }

        $user->load('parkingLocation');

        $user->loadCount([
            'issueReports',
            'dailyTrafficReports',
            'backupRequests',
            'assignedReports',
        ]);

        return view('user-management.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->ensureAdminOperational();
        $this->ensureOperationalUser($user);

        $user->load('parkingLocation');
        $parkingLocations = $this->getActiveParkingLocations();

        return view('user-management.edit', compact('user', 'parkingLocations'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdminOperational();
        $this->ensureOperationalUser($user);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'role' => [
                'required',
                Rule::in(['petugas', 'teknisi']),
            ],
            'parking_location_id' => [
                'required',
                'integer',
                'exists:parking_locations,id',
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Tidak Aktif']),
            ],
        ], [
            'username.required' => 'NIK wajib diisi.',
            'username.unique' => 'NIK sudah digunakan oleh akun lain.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role hanya boleh Petugas Parkir atau Teknisi Vendor.',
            'parking_location_id.required' => 'Lokasi operasional wajib dipilih.',
            'parking_location_id.exists' => 'Lokasi operasional tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $user->update([
            'username' => $validated['username'],
            'nip' => $validated['username'],
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'parking_location_id' => $validated['parking_location_id'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensureAdminOperational();
        $this->ensureOperationalUser($user);

        $user->update([
            'status' => 'Tidak Aktif',
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }

    public function toggleStatus(User $user)
    {
        $this->ensureAdminOperational();
        $this->ensureOperationalUser($user);

        $newStatus = $user->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif';

        $user->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status akun berhasil diubah menjadi ' . $newStatus . '.');
    }

    public function resetPassword(User $user)
    {
        $this->ensureAdminOperational();
        $this->ensureOperationalUser($user);

        $newPassword = $this->generateInitialPassword();

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', 'Password akun berhasil direset.')
            ->with('initial_password', $newPassword);
    }

    private function ensureAdminOperational(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin Operasional yang dapat mengelola akun pengguna.');
        }
    }

    private function ensureOperationalUser(User $user): void
    {
        if (!in_array($user->role, ['petugas', 'teknisi'], true)) {
            abort(403, 'Akun ini bukan akun Petugas Parkir atau Teknisi Vendor.');
        }
    }

    private function getActiveParkingLocations()
    {
        return ParkingLocation::query()
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();
    }

    private function generateInitialPassword(): string
    {
        return 'EP-' . strtoupper(Str::random(3)) . random_int(100, 999);
    }
}