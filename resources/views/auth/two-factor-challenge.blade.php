{{--
    View ini dipakai Fortify saat user dengan 2FA aktif berhasil login
    dengan email+password, sebelum benar-benar masuk. Pakai komponen Blade
    bawaan Breeze (x-guest-layout, x-input-label, dst) — otomatis tersedia
    setelah `php artisan breeze:install blade`.
--}}
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan kode dari aplikasi authenticator kamu (Google Authenticator,
        Authy, dll) untuk melanjutkan.
    </div>

    <div x-data="{ recovery: false }">
        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div x-show="! recovery">
                <x-input-label for="code" value="Kode Autentikasi" />
                <x-text-input
                    id="code"
                    class="block mt-1 w-full"
                    type="text"
                    inputmode="numeric"
                    name="code"
                    autofocus
                    autocomplete="one-time-code"
                    x-ref="code"
                />
            </div>

            <div x-show="recovery" style="display: none;">
                <x-input-label for="recovery_code" value="Kode Pemulihan" />
                <x-text-input
                    id="recovery_code"
                    class="block mt-1 w-full"
                    type="text"
                    name="recovery_code"
                    autocomplete="one-time-code"
                    x-ref="recovery_code"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button
                    type="button"
                    class="text-sm text-gray-600 underline cursor-pointer bg-transparent border-0"
                    x-on:click="
                        recovery = ! recovery;
                        $nextTick(() => {
                            if (recovery) { $refs.recovery_code.focus(); $refs.code.value = ''; }
                            else { $refs.code.focus(); $refs.recovery_code.value = ''; }
                        });
                    "
                >
                    <span x-show="! recovery">Pakai kode pemulihan</span>
                    <span x-show="recovery" style="display: none;">Pakai kode authenticator</span>
                </button>

                <x-primary-button class="ms-4">
                    Masuk
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
