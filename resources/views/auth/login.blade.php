@extends('layouts.public')

@section('title', 'Masuk')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Area anggota</p>
                <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Selamat datang kembali</h1>
                <p class="mt-4 text-indigo-100">
                    Masuk untuk mengelola dokumen, berita, dan program pada repository Anda.
                </p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(28rem,36rem)] items-start">
            <div class="hidden lg:block rounded-2xl bg-white border border-indigo-100 p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Repository Anda</p>
                <h2 class="mt-3 text-2xl font-bold text-slate-900">Satu tempat untuk menemukan informasi penting.</h2>
                <p class="mt-4 text-slate-500 leading-7">
                    Akses koleksi dokumen, informasi pelatihan, dan berita terbaru dengan akun Anda.
                </p>
                <a href="{{ route('home') }}" class="mt-8 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Kembali ke halaman utama <span class="ml-2" aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[30rem]">
                <div class="mb-9">
                    <h2 class="text-3xl font-bold text-slate-900">Masuk ke akun</h2>
                    <p class="mt-2 text-sm text-slate-500">Gunakan email dan password Anda untuk melanjutkan.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-7">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
                        <x-text-input id="email" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
                        <x-text-input id="password" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <x-primary-button class="w-full justify-center rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">
                        {{ __('Log in') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </section>
@endsection
