<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $canEditIdentity = in_array($user->role, ['admin', 'manajer'], true);

        /*
        |--------------------------------------------------------------------------
        | Fallback dan Pembatasan Data Profil
        |--------------------------------------------------------------------------
        |
        | Sistem memakai NIK/username sebagai login. Untuk menjaga konsistensi
        | data operasional, perubahan Nama Lengkap dan NIK dari halaman profil
        | hanya boleh dilakukan oleh Admin Operasional dan Manajer Operasional.
        |
        | Petugas Parkir dan Teknisi Vendor tetap dapat mengubah email, nomor
        | telepon, foto profil, dan password. Perubahan nama/NIK Petugas/Teknisi
        | dilakukan melalui Admin Operasional pada menu User Management.
        |
        | Pada beberapa kondisi form multipart dengan method spoofing PUT dapat
        | menyebabkan input text tidak terbaca sempurna, sementara file tetap
        | masuk. Karena itu data utama tetap diberi fallback dari data user aktif.
        |
        */
        $currentFullName = $user->full_name ?: $user->name;

        $request->merge([
            'username' => $canEditIdentity
                ? ($request->input('username') ?: $user->username)
                : $user->username,

            'full_name' => $canEditIdentity
                ? ($request->input('full_name') ?: $currentFullName)
                : $currentFullName,

            'email' => $request->has('email') ? $request->input('email') : $user->email,
            'phone' => $request->has('phone') ? $request->input('phone') : $user->phone,
        ]);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
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
            'profile_photo' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ], [
            'username.required' => 'NIK login wajib diisi.',
            'username.max' => 'NIK login maksimal 50 karakter.',
            'username.regex' => 'NIK login hanya boleh berisi huruf, angka, titik, garis bawah, atau strip.',
            'username.unique' => 'NIK login sudah digunakan oleh akun lain.',

            'full_name.required' => 'Nama lengkap wajib diisi.',

            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',

            'profile_photo.file' => 'Foto profil tidak valid.',
            'profile_photo.image' => 'Foto profil harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat JPG, JPEG, atau PNG.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 5 MB.',
        ]);

        $photoPath = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $uploadedFile = $request->file('profile_photo');

            if ($uploadedFile && $uploadedFile->isValid()) {
                $oldPhotoPath = $this->normalizeStoragePath($photoPath);

                if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                    Storage::disk('public')->delete($oldPhotoPath);
                }

                $extension = strtolower($uploadedFile->getClientOriginalExtension());

                if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                    return back()
                        ->withErrors([
                            'profile_photo' => 'Format foto profil harus JPG, JPEG, atau PNG.',
                        ])
                        ->withInput();
                }

                $fileName = 'user-' . $user->id . '-' . now()->format('YmdHis') . '-' . Str::random(10) . '.' . $extension;

                /*
                |--------------------------------------------------------------------------
                | Simpan Foto Profil
                |--------------------------------------------------------------------------
                |
                | File akan tersimpan di:
                | storage/app/public/profile-photos/namafile.jpg
                |
                | Path yang disimpan ke database:
                | profile-photos/namafile.jpg
                |
                | Agar bisa diakses browser, pastikan sudah menjalankan:
                | php artisan storage:link
                |
                */
                $photoPath = $uploadedFile->storeAs('profile-photos', $fileName, 'public');
            }
        }

        $data = [
            'username' => $validated['username'],
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'profile_photo' => $photoPath,
        ];

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Kolom NIK Jika Ada
        |--------------------------------------------------------------------------
        |
        | Sistem utama login menggunakan username sebagai NIK.
        | Jika pada tabel users juga tersedia kolom nik, maka nilainya ikut
        | disamakan agar data akun tetap konsisten.
        |
        */
        if (
            array_key_exists('nik', $user->getAttributes()) ||
            in_array('nik', $user->getFillable(), true)
        ) {
            $data['nik'] = $validated['username'];
        }

        $user->forceFill($data)->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Password saat ini tidak sesuai.',
                ])
                ->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Password berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        $photoPath = $this->normalizeStoragePath($user->profile_photo);

        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }

        $user->forceFill([
            'profile_photo' => null,
        ])->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    private function normalizeStoragePath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        if (str_starts_with($path, 'storage/')) {
            return substr($path, strlen('storage/'));
        }

        return ltrim($path, '/');
    }
}
