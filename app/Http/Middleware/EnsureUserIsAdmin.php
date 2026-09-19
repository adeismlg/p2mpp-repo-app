<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware alias: 'admin'
 * Hanya izinkan role admin. Dipakai untuk aksi sensitif:
 * kelola kategori dokumen & manajemen staff/user.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Hanya admin yang memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
