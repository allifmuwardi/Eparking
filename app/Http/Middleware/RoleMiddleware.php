<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = $this->normalizeRole(Auth::user()->role);

        $allowedRoles = [];

        foreach ($roles as $role) {
            foreach (explode(',', $role) as $singleRole) {
                $normalizedRole = $this->normalizeRole($singleRole);

                if (!empty($normalizedRole)) {
                    $allowedRoles[] = $normalizedRole;
                }
            }
        }

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    private function normalizeRole(?string $role): string
    {
        $role = strtolower(trim($role ?? ''));

        return match ($role) {
            /*
            |--------------------------------------------------------------------------
            | Petugas Parkir
            |--------------------------------------------------------------------------
            */
            'petugas parkir',
            'petugas',
            'parking officer' => 'petugas',

            /*
            |--------------------------------------------------------------------------
            | Teknisi Vendor
            |--------------------------------------------------------------------------
            */
            'teknisi vendor',
            'teknisi',
            'technician',
            'vendor technician' => 'teknisi',

            /*
            |--------------------------------------------------------------------------
            | Manajer Operasional
            |--------------------------------------------------------------------------
            */
            'manajer operasional',
            'manajer',
            'manager',
            'operational manager' => 'manajer',

            /*
            |--------------------------------------------------------------------------
            | Admin Operasional
            |--------------------------------------------------------------------------
            | Catatan:
            | Di database role tetap disimpan sebagai "admin".
            | Namun di interface dan konsep sistem disebut Admin Operasional,
            | bukan Admin Sistem.
            */
            'admin operasional',
            'administrator',
            'admin',
            'operational admin' => 'admin',

            default => $role,
        };
    }
}