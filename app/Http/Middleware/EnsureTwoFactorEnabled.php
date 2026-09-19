<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware alias: '2fa'
 * Memaksa akun staff/admin mengaktifkan 2FA sebelum bisa memakai panel
 * /admin. Kalau belum, mereka diarahkan ke halaman profil untuk setup
 * dulu (bukan langsung diblokir/logout).
 *
 * Kalau kamu belum siap memaksa (misal staf baru dikasih waktu), cukup
 * jangan pasang middleware ini di grup route /admin — semua fitur 2FA
 * tetap ada & opsional dipakai lewat halaman profil.
 */
class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isStaff() && ! $user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('profile.edit')
                ->with('status', 'Demi keamanan, aktifkan 2FA sebelum melanjutkan.');
        }

        return $next($request);
    }
}
