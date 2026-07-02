<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->must_change_password ?? false) {
                return redirect()->route('profile.index');
            }

            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'NIK wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Login Berbasis NIK
        |--------------------------------------------------------------------------
        | Secara database kolom yang dipakai tetap "username",
        | tetapi secara konsep dan tampilan sistem disebut sebagai NIK.
        */
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status' => 'Aktif',
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | Role yang Diizinkan Login
            |--------------------------------------------------------------------------
            | admin berarti Admin Operasional, bukan Admin Sistem.
            */
            if (!in_array($user->role, ['petugas', 'teknisi', 'manajer', 'admin'], true)) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors([
                        'username' => 'Akun ini tidak memiliki akses ke sistem operasional parkir.',
                    ])
                    ->onlyInput('username');
            }

            $request->session()->regenerate();

            /*
            |--------------------------------------------------------------------------
            | Wajib Ganti Password Awal
            |--------------------------------------------------------------------------
            | Jika akun baru dibuat atau password direset oleh Admin Operasional,
            | user diarahkan ke Profil Saya untuk mengganti password.
            */
            if ($user->must_change_password ?? false) {
                return redirect()
                    ->route('profile.index')
                    ->with('warning', 'Silakan ganti password awal Anda sebelum menggunakan sistem.');
            }

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login berhasil. Selamat datang di Sistem Penanganan Kendala Parkir.');
        }

        return back()
            ->withErrors([
                'username' => 'NIK atau password tidak sesuai.',
            ])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}