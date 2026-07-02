<?php

namespace App\Http\Controllers;

use App\Models\BackupItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BackupItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $items = BackupItem::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('item_code', 'like', "%{$search}%")
                        ->orWhere('item_name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('storage_location', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => BackupItem::count(),
            'available' => BackupItem::where('status', 'Tersedia')->where('stock', '>', 0)->count(),
            'not_available' => BackupItem::where('status', 'Tidak Tersedia')->orWhere('stock', '<=', 0)->count(),
            'total_stock' => BackupItem::sum('stock'),
        ];

        return view('master.backup-items.index', compact('items', 'search', 'summary'));
    }

    public function create()
    {
        return view('master.backup-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:50',
                'unique:backup_items,item_code',
            ],
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'nullable',
                'string',
                'max:255',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'unit' => [
                'required',
                'string',
                'max:50',
            ],
            'storage_location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'item_code.required' => 'Kode barang wajib diisi.',
            'item_code.unique' => 'Kode barang sudah digunakan.',
            'item_name.required' => 'Nama barang wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'unit.required' => 'Satuan wajib diisi.',
        ]);

        $validated['status'] = ((int) $validated['stock'] > 0) ? 'Tersedia' : 'Tidak Tersedia';

        BackupItem::create($validated);

        return redirect()
            ->route('backup-items.index')
            ->with('success', 'Data barang backup berhasil ditambahkan.');
    }

    public function show(BackupItem $backupItem)
    {
        return view('master.backup-items.show', compact('backupItem'));
    }

    public function edit(BackupItem $backupItem)
    {
        return view('master.backup-items.edit', compact('backupItem'));
    }

    public function update(Request $request, BackupItem $backupItem)
    {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('backup_items', 'item_code')->ignore($backupItem->id),
            ],
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'nullable',
                'string',
                'max:255',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'unit' => [
                'required',
                'string',
                'max:50',
            ],
            'storage_location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'item_code.required' => 'Kode barang wajib diisi.',
            'item_code.unique' => 'Kode barang sudah digunakan.',
            'item_name.required' => 'Nama barang wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'unit.required' => 'Satuan wajib diisi.',
        ]);

        $validated['status'] = ((int) $validated['stock'] > 0) ? 'Tersedia' : 'Tidak Tersedia';

        $backupItem->update($validated);

        return redirect()
            ->route('backup-items.show', $backupItem)
            ->with('success', 'Data barang backup berhasil diperbarui.');
    }

    public function destroy(BackupItem $backupItem)
    {
        $backupItem->delete();

        return redirect()
            ->route('backup-items.index')
            ->with('success', 'Data barang backup berhasil dihapus.');
    }
}