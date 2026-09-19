<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_see_two_factor_settings_on_their_profile(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($user)->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Autentikasi Dua Faktor (2FA)');
    }

    public function test_staff_can_start_two_factor_setup_after_confirming_password(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post(route('two-factor.enable'));

        $response->assertRedirect();
        $this->assertNotNull($user->refresh()->two_factor_secret);
        $this->assertNull($user->two_factor_confirmed_at);
    }

    public function test_two_factor_qr_code_and_recovery_codes_require_password_confirmation(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
            'two_factor_secret' => encrypt('secret'),
        ]);

        $this->actingAs($user)
            ->get(route('two-factor.qr-code'))
            ->assertRedirect(route('password.confirm'));

        $this->actingAs($user)
            ->get(route('two-factor.recovery-codes'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_staff_without_confirmed_two_factor_authentication_can_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_staff_with_confirmed_two_factor_authentication_can_still_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
            'two_factor_secret' => encrypt('secret'),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}
