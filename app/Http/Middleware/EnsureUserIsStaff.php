<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware alias: 'staff'
 * Izinkan role admin ATAU editor masuk ke panel /admin.
 * User biasa (role 'guest', dari pendaftaran publik) akan ditolak.
 */
class EnsureUserIsStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isStaff()) {
            abort(403, 'Akun kamu belum memiliki akses ke panel admin. Hubungi admin untuk diberi akses.');
        }

        return $next($request);
    }
}
