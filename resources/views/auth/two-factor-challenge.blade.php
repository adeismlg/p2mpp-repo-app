@extends('layouts.public')

@section('title', 'Verifikasi Dua Langkah')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Keamanan akun</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Verifikasi dua langkah</h1>
            <p class="mt-4 max-w-2xl text-indigo-100">Gunakan authenticator atau kode pemulihan untuk menyelesaikan proses masuk.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="max-w-xl mx-auto rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[26rem]">
            <h2 class="text-3xl font-bold text-slate-900">Masukkan kode</h2>
            <p class="mt-2 text-sm text-slate-500">Pilih salah satu metode verifikasi yang tersedia.</p>

            <div x-data="{ recovery: false }" class="mt-9">
                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-7">
                    @csrf
                    <div x-show="! recovery">
                        <x-input-label for="code" value="Kode Authenticator" class="text-slate-700" />
                        <x-text-input id="code" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code" x-ref="code" />
                    </div>

                    <div x-show="recovery" style="display: none;">
                        <x-input-label for="recovery_code" value="Kode Pemulihan" class="text-slate-700" />
                        <x-text-input id="recovery_code" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="text" name="recovery_code" autocomplete="one-time-code" x-ref="recovery_code" />
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline" x-on:click="recovery = ! recovery; $nextTick(() => { if (recovery) { $refs.recovery_code.focus(); $refs.code.value = ''; } else { $refs.code.focus(); $refs.recovery_code.value = ''; } });">
                            <span x-show="! recovery">Pakai kode pemulihan</span>
                            <span x-show="recovery" style="display: none;">Pakai kode authenticator</span>
                        </button>
                        <x-primary-button class="rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">Masuk</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
