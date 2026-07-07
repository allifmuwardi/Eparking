<?php

namespace App\Http\Controllers;

use App\Models\BackupItem;
use App\Models\BackupRequest;
use App\Models\Notification;
use App\Models\ParkingLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['petugas', 'admin', 'manajer'], true)) {
            abort(403, 'Anda tidak memiliki akses ke permintaan barang backup.');
        }

        $search = $request->search;
        $status = $request->status;

        $backupRequests = BackupRequest::with([
                'parkingLocation',
                'backupItem',
                'requester',
                'verifier',
                'processor',
            ])
            ->when($user->role === 'petugas', function ($query) use ($user) {
                if ($user->parking_location_id) {
                    $query->where('parking_location_id', $user->parking_location_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('request_number', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('priority', 'like', "%{$search}%")
                        ->orWhereHas('backupItem', function ($itemQuery) use ($search) {
                            $itemQuery->where('item_name', 'like', "%{$search}%")
                                ->orWhere('item_code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('parkingLocation', function ($locationQuery) use ($search) {
                            $locationQuery->where('location_name', 'like', "%{$search}%")
                                ->orWhere('location_code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('requester', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('full_name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backup-requests.index', compact('backupRequests', 'search', 'status'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengajukan permintaan barang backup.');
        }

        if (!$user->parking_location_id) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $locations = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();

        $selectedLocation = $locations->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        $items = BackupItem::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->orderBy('item_name')
            ->get();

        return view('backup-requests.create', compact('locations', 'selectedLocation', 'items'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengajukan permintaan barang backup.');
        }

        if (!$user->parking_location_id) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Akun Anda belum memiliki lokasi operasional. Silakan hubungi Admin Operasional.');
        }

        $selectedLocation = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        | parking_location_id tidak lagi divalidasi dari input user.
        | Lokasi backup dikunci mengikuti lokasi operasional user login.
        */
        $validated = $request->validate([
            'backup_item_id' => 'required|exists:backup_items,id',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|in:Rendah,Sedang,Tinggi,Darurat',
            'reason' => 'required|string',
        ], [
            'backup_item_id.required' => 'Barang backup wajib dipilih.',
            'backup_item_id.exists' => 'Barang backup tidak valid.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang minimal 1.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'reason.required' => 'Alasan permintaan wajib diisi.',
        ]);

        $backupItem = BackupItem::findOrFail($validated['backup_item_id']);

        if ($backupItem->status !== 'Tersedia') {
            return back()
                ->withInput()
                ->withErrors([
                    'backup_item_id' => 'Barang backup yang dipilih sedang tidak tersedia.',
                ]);
        }

        if ($validated['quantity'] > $backupItem->stock) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'Jumlah permintaan melebihi stok barang yang tersedia. Stok saat ini: ' . $backupItem->stock,
                ]);
        }

        $backupRequest = BackupRequest::create([
            'request_number' => $this->generateRequestNumber(),
            'user_id' => $user->id,
            'parking_location_id' => $user->parking_location_id,
            'backup_item_id' => $validated['backup_item_id'],
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'priority' => $validated['priority'],
            'status' => 'Menunggu Verifikasi',
        ]);

        $managers = User::where('role', 'manajer')
            ->where('status', 'Aktif')
            ->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Permintaan Barang Backup Baru',
                'message' => 'Terdapat permintaan barang backup baru dari ' .
                    ($selectedLocation->location_name ?? 'lokasi operasional') .
                    ' yang menunggu verifikasi.',
                'type' => 'backup_request',
                'reference_id' => $backupRequest->id,
                'reference_type' => 'backup_requests',
                'url' => route('backup-requests.show', $backupRequest),
            ]);
        }

        return redirect()
            ->route('backup-requests.index')
            ->with('success', 'Permintaan barang backup berhasil dikirim dan menunggu verifikasi Manajer Operasional.');
    }

    public function show(BackupRequest $backupRequest)
    {
        $user = Auth::user();

        if ($user->role === 'petugas') {
            if (!$user->parking_location_id || $backupRequest->parking_location_id !== $user->parking_location_id) {
                abort(403, 'Anda tidak memiliki akses ke permintaan barang backup ini.');
            }
        } elseif (!in_array($user->role, ['admin', 'manajer'], true)) {
            abort(403, 'Anda tidak memiliki akses ke permintaan barang backup ini.');
        }

        $backupRequest->load([
            'requester',
            'parkingLocation',
            'backupItem',
            'verifier',
            'processor',
        ]);

        return view('backup-requests.show', compact('backupRequest'));
    }

    public function edit(BackupRequest $backupRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengubah permintaan backup.');
        }

        /*
        |--------------------------------------------------------------------------
        | Edit tetap hanya boleh oleh pembuat request
        |--------------------------------------------------------------------------
        | Walaupun history backup terlihat untuk satu lokasi, perubahan data hanya
        | boleh dilakukan oleh petugas yang membuat permintaan tersebut.
        */
        if ($backupRequest->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah permintaan ini.');
        }

        if (!$user->parking_location_id || $backupRequest->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah permintaan ini.');
        }

        if ($backupRequest->status !== 'Menunggu Verifikasi') {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diubah.');
        }

        $locations = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->orderBy('location_name')
            ->get();

        $selectedLocation = $locations->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        $items = BackupItem::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->orderBy('item_name')
            ->get();

        return view('backup-requests.edit', compact('backupRequest', 'locations', 'selectedLocation', 'items'));
    }

    public function update(Request $request, BackupRequest $backupRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat mengubah permintaan backup.');
        }

        if ($backupRequest->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah permintaan ini.');
        }

        if (!$user->parking_location_id || $backupRequest->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah permintaan ini.');
        }

        if ($backupRequest->status !== 'Menunggu Verifikasi') {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diubah.');
        }

        $selectedLocation = ParkingLocation::where('id', $user->parking_location_id)
            ->where('status', 'Aktif')
            ->first();

        if (!$selectedLocation) {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Lokasi operasional akun Anda tidak aktif. Silakan hubungi Admin Operasional.');
        }

        $validated = $request->validate([
            'backup_item_id' => 'required|exists:backup_items,id',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|in:Rendah,Sedang,Tinggi,Darurat',
            'reason' => 'required|string',
        ], [
            'backup_item_id.required' => 'Barang backup wajib dipilih.',
            'backup_item_id.exists' => 'Barang backup tidak valid.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang minimal 1.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'reason.required' => 'Alasan permintaan wajib diisi.',
        ]);

        $backupItem = BackupItem::findOrFail($validated['backup_item_id']);

        if ($backupItem->status !== 'Tersedia') {
            return back()
                ->withInput()
                ->withErrors([
                    'backup_item_id' => 'Barang backup yang dipilih sedang tidak tersedia.',
                ]);
        }

        if ($validated['quantity'] > $backupItem->stock) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'Jumlah permintaan melebihi stok barang yang tersedia. Stok saat ini: ' . $backupItem->stock,
                ]);
        }

        $backupRequest->update([
            'parking_location_id' => $user->parking_location_id,
            'backup_item_id' => $validated['backup_item_id'],
            'quantity' => $validated['quantity'],
            'priority' => $validated['priority'],
            'reason' => $validated['reason'],
        ]);

        return redirect()
            ->route('backup-requests.show', $backupRequest)
            ->with('success', 'Permintaan barang backup berhasil diperbarui.');
    }

    public function destroy(BackupRequest $backupRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'petugas') {
            abort(403, 'Hanya Petugas Parkir yang dapat menghapus permintaan backup.');
        }

        if ($backupRequest->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus permintaan ini.');
        }

        if (!$user->parking_location_id || $backupRequest->parking_location_id !== $user->parking_location_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus permintaan ini.');
        }

        if ($backupRequest->status !== 'Menunggu Verifikasi') {
            return redirect()
                ->route('backup-requests.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat dihapus.');
        }

        if ($backupRequest->handover_photo && Storage::disk('public')->exists($backupRequest->handover_photo)) {
            Storage::disk('public')->delete($backupRequest->handover_photo);
        }

        $backupRequest->delete();

        return redirect()
            ->route('backup-requests.index')
            ->with('success', 'Permintaan barang backup berhasil dihapus.');
    }

    public function approve(Request $request, BackupRequest $backupRequest)
    {
        if (Auth::user()->role !== 'manajer') {
            abort(403, 'Hanya Manajer Operasional yang dapat menyetujui permintaan backup.');
        }

        if ($backupRequest->status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Permintaan ini sudah pernah diproses.');
        }

        $validated = $request->validate([
            'verification_note' => 'nullable|string',
        ]);

        $backupRequest->update([
            'status' => 'Disetujui',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'verification_note' => $validated['verification_note'] ?? null,
        ]);

        Notification::create([
            'user_id' => $backupRequest->user_id,
            'title' => 'Permintaan Backup Disetujui',
            'message' => 'Permintaan barang backup Anda telah disetujui oleh Manajer Operasional.',
            'type' => 'backup_request',
            'reference_id' => $backupRequest->id,
            'reference_type' => 'backup_requests',
            'url' => route('backup-requests.show', $backupRequest),
        ]);

        $admins = User::where('role', 'admin')
            ->where('status', 'Aktif')
            ->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permintaan Backup Siap Diproses',
                'message' => 'Terdapat permintaan barang backup yang telah disetujui Manajer dan siap diproses.',
                'type' => 'backup_request',
                'reference_id' => $backupRequest->id,
                'reference_type' => 'backup_requests',
                'url' => route('backup-requests.show', $backupRequest),
            ]);
        }

        return redirect()
            ->route('backup-requests.show', $backupRequest)
            ->with('success', 'Permintaan barang backup berhasil disetujui dan diteruskan ke Admin Operasional.');
    }

    public function reject(Request $request, BackupRequest $backupRequest)
    {
        if (Auth::user()->role !== 'manajer') {
            abort(403, 'Hanya Manajer Operasional yang dapat menolak permintaan backup.');
        }

        if ($backupRequest->status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Permintaan ini sudah pernah diproses.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $backupRequest->update([
            'status' => 'Ditolak',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Notification::create([
            'user_id' => $backupRequest->user_id,
            'title' => 'Permintaan Backup Ditolak',
            'message' => 'Permintaan barang backup Anda ditolak oleh Manajer Operasional.',
            'type' => 'backup_request',
            'reference_id' => $backupRequest->id,
            'reference_type' => 'backup_requests',
            'url' => route('backup-requests.show', $backupRequest),
        ]);

        return redirect()
            ->route('backup-requests.show', $backupRequest)
            ->with('success', 'Permintaan barang backup berhasil ditolak.');
    }

    public function process(BackupRequest $backupRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin Operasional yang dapat memproses permintaan backup.');
        }

        if ($backupRequest->status !== 'Disetujui') {
            return back()->with('error', 'Permintaan hanya dapat diproses setelah disetujui Manajer Operasional.');
        }

        $backupRequest->update([
            'status' => 'Dalam Proses',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $backupRequest->user_id,
            'title' => 'Permintaan Backup Diproses',
            'message' => 'Permintaan barang backup Anda sedang diproses oleh Admin Operasional.',
            'type' => 'backup_request',
            'reference_id' => $backupRequest->id,
            'reference_type' => 'backup_requests',
            'url' => route('backup-requests.show', $backupRequest),
        ]);

        return redirect()
            ->route('backup-requests.show', $backupRequest)
            ->with('success', 'Permintaan barang backup masuk ke status Dalam Proses.');
    }

    public function complete(Request $request, BackupRequest $backupRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin Operasional yang dapat menyelesaikan permintaan backup.');
        }

        if ($backupRequest->status !== 'Dalam Proses') {
            return back()->with('error', 'Permintaan hanya dapat diselesaikan jika sedang Dalam Proses.');
        }

        $validated = $request->validate([
            'handover_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'handover_photo.image' => 'File bukti penyerahan harus berupa gambar.',
            'handover_photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'handover_photo.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        $photoPath = $backupRequest->handover_photo;

        if ($request->hasFile('handover_photo')) {
            $photoPath = $request->file('handover_photo')->store('backup-handover', 'public');
        }

        try {
            DB::transaction(function () use ($backupRequest, $photoPath) {
                $backupRequest->load('backupItem');

                if (!$backupRequest->backupItem) {
                    throw new \Exception('Data barang backup tidak ditemukan.');
                }

                if ($backupRequest->quantity > $backupRequest->backupItem->stock) {
                    throw new \Exception('Stok barang tidak mencukupi. Stok saat ini: ' . $backupRequest->backupItem->stock);
                }

                $backupRequest->backupItem->decrement('stock', $backupRequest->quantity);

                $freshItem = $backupRequest->backupItem->fresh();

                if ($freshItem && $freshItem->stock <= 0) {
                    $freshItem->update([
                        'status' => 'Tidak Tersedia',
                    ]);
                }

                $backupRequest->update([
                    'status' => 'Selesai',
                    'completed_at' => now(),
                    'handover_photo' => $photoPath,
                ]);
            });
        } catch (\Exception $e) {
            if (
                $request->hasFile('handover_photo')
                && $photoPath
                && Storage::disk('public')->exists($photoPath)
            ) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->withErrors([
                'handover_photo' => $e->getMessage(),
            ]);
        }

        Notification::create([
            'user_id' => $backupRequest->user_id,
            'title' => 'Permintaan Backup Selesai',
            'message' => 'Permintaan barang backup Anda telah selesai diproses oleh Admin Operasional.',
            'type' => 'backup_request',
            'reference_id' => $backupRequest->id,
            'reference_type' => 'backup_requests',
            'url' => route('backup-requests.show', $backupRequest),
        ]);

        return redirect()
            ->route('backup-requests.show', $backupRequest)
            ->with('success', 'Permintaan barang backup berhasil diselesaikan dan stok barang telah diperbarui.');
    }

    private function generateRequestNumber(): string
    {
        $date = now()->format('Ymd');

        $lastRequest = BackupRequest::whereDate('created_at', now()->toDateString())
            ->latest('id')
            ->first();

        $nextNumber = $lastRequest ? ((int) substr($lastRequest->request_number, -4)) + 1 : 1;

        return 'BRQ-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}