<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Menerima satu atau lebih permission slug (dipisah koma).
     * User diizinkan jika memiliki SALAH SATU dari permission yang diberikan.
     * Super Admin otomatis lolos semua pemeriksaan.
     *
     * Contoh penggunaan di route:
     *   ->middleware('permission:view_pegawai')
     *   ->middleware('permission:create_pegawai,edit_pegawai')
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        // Super Admin selalu diizinkan
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Cek apakah user memiliki salah satu permission
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
