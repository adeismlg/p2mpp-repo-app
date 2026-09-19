<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Fortify;

/**
 * File ini MENIMPA app/Providers/FortifyServiceProvider.php yang dibuat
 * otomatis oleh `php artisan fortify:install`. Kita arahkan setiap view
 * Fortify ke Blade view Breeze yang sudah ada, supaya Fortify menangani
 * LOGIKA (login, 2FA, dst) tapi TAMPILANNYA tetap punya Breeze.
 */
class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));

        // View baru khusus 2FA, dibuat di langkah berikut.
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = mb_strtolower($request->input(Fortify::username())).'|'.$request->ip();

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
