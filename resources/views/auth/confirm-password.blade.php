@extends('layouts.public')

@section('title', 'Konfirmasi Password')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Keamanan akun</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Konfirmasi identitas Anda</h1>
            <p class="mt-4 max-w-2xl text-indigo-100">Masukkan password sebelum melanjutkan ke area yang aman.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="max-w-xl mx-auto rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[30rem]">
            <h2 class="text-3xl font-bold text-slate-900">Konfirmasi password</h2>
            <p class="mt-2 text-sm text-slate-500">Ini adalah langkah keamanan tambahan untuk melindungi akun Anda.</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="mt-9 space-y-7">
                @csrf
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
                    <x-text-input id="password" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autofocus autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button class="rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">Konfirmasi</x-primary-button>
                </div>
            </form>
        </div>
    </section>
@endsection
