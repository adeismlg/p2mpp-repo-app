{{--
    Tempel/include potongan ini di resources/views/profile/edit.blade.php
    (file bawaan Breeze), contoh:

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            @include('profile.partials.two-factor-authentication-form')
        </div>

    Hanya tampil untuk user staff/admin — 2FA tidak relevan buat user biasa.
--}}
@if (auth()->user()->isStaff())
<section x-data="twoFactorAuth()" x-init="init()">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Autentikasi Dua Faktor (2FA)</h2>
        <p class="mt-1 text-sm text-gray-600">
            Tambahkan lapisan keamanan ekstra ke akun kamu memakai aplikasi
            authenticator seperti Google Authenticator atau Authy.
        </p>
    </header>

    <div class="mt-5">
        <template x-if="!enabled">
            <div>
                <p class="text-sm text-gray-600 mb-4">2FA belum aktif di akun kamu.</p>
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        x-on:click="enable()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700"
                    >
                        Aktifkan 2FA
                    </button>
                    <a href="{{ route('password.confirm') }}" class="text-sm text-indigo-600 underline hover:text-indigo-800">
                        Konfirmasi password dulu
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    Untuk mengaktifkan atau menonaktifkan 2FA, konfirmasi password terlebih dahulu.
                </p>
            </div>
        </template>

        <template x-if="enabled && !confirming">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    2FA Aktif
                </span>
                <button type="button" x-on:click="showRecoveryCodes()" class="text-sm text-indigo-600 underline">
                    Lihat kode pemulihan
                </button>
                <button type="button" x-on:click="disable()" class="text-sm text-red-600 underline">
                    Nonaktifkan
                </button>
            </div>
        </template>

        <template x-if="confirming">
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-3">
                    Scan QR code ini dengan aplikasi authenticator kamu, lalu masukkan
                    kode 6 digit yang muncul untuk konfirmasi.
                </p>
                <div class="mb-4" x-html="qrCode"></div>

                <div class="max-w-xs">
                    <x-input-label for="confirm_code" value="Kode Konfirmasi" />
                    <x-text-input id="confirm_code" x-model="confirmationCode" class="block mt-1 w-full" type="text" inputmode="numeric" autocomplete="one-time-code" />
                    <p class="text-sm text-red-600 mt-1" x-show="error" x-text="error"></p>
                </div>

                <div class="mt-4 flex gap-3">
                    <button type="button" x-on:click="confirm()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                        Konfirmasi
                    </button>
                    <button type="button" x-on:click="cancel()" class="text-sm text-gray-600 underline">
                        Batal
                    </button>
                </div>
            </div>
        </template>

        <template x-if="recoveryCodes.length > 0">
            <div class="mt-4 p-4 bg-slate-50 rounded-lg max-w-md">
                <p class="text-sm font-semibold mb-2">Simpan kode pemulihan ini di tempat aman:</p>
                <ul class="font-mono text-sm space-y-1">
                    <template x-for="code in recoveryCodes" :key="code">
                        <li x-text="code"></li>
                    </template>
                </ul>
                <p class="text-xs text-gray-500 mt-2">
                    Setiap kode hanya bisa dipakai sekali sebagai pengganti kode authenticator
                    kalau HP kamu hilang/rusak.
                </p>
            </div>
        </template>
    </div>
</section>

<script>
    function twoFactorAuth() {
        return {
            enabled: {{ auth()->user()->hasEnabledTwoFactorAuthentication() ? 'true' : 'false' }},
            confirming: false,
            qrCode: '',
            confirmationCode: '',
            recoveryCodes: [],
            error: '',

            init() {},

            csrf() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            },

            async enable() {
                await fetch('/user/two-factor-authentication', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                });
                await this.loadQrCode();
                this.confirming = true;
            },

            async loadQrCode() {
                const res = await fetch('/user/two-factor-qr-code', {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();
                this.qrCode = data.svg;
            },

            async confirm() {
                this.error = '';
                const res = await fetch('/user/confirmed-two-factor-authentication', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ code: this.confirmationCode }),
                });

                if (! res.ok) {
                    const data = await res.json();
                    this.error = data.errors?.code?.[0] ?? 'Kode salah, coba lagi.';
                    return;
                }

                this.enabled = true;
                this.confirming = false;
                await this.showRecoveryCodes();
            },

            async showRecoveryCodes() {
                const res = await fetch('/user/two-factor-recovery-codes', {
                    headers: { 'Accept': 'application/json' },
                });
                this.recoveryCodes = await res.json();
            },

            async disable() {
                if (! confirm('Yakin nonaktifkan 2FA? Akun jadi hanya dilindungi password.')) return;

                await fetch('/user/two-factor-authentication', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                });

                this.enabled = false;
                this.recoveryCodes = [];
            },

            cancel() {
                this.confirming = false;
            },
        };
    }
</script>
@endif
