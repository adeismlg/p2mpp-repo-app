@extends('layouts.public')

@section('title', 'Lupa Password')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Bantuan akun</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Atur ulang password</h1>
            <p class="mt-4 max-w-2xl text-indigo-100">Kami akan mengirimkan tautan untuk membuat password baru ke email Anda.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="max-w-xl mx-auto rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[30rem]">
            <h2 class="text-3xl font-bold text-slate-900">Lupa password?</h2>
            <p class="mt-2 text-sm text-slate-500">Masukkan email yang terdaftar pada akun Anda.</p>

            <x-auth-session-status class="mt-5" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="mt-9 space-y-7">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
                    <x-text-input id="email" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Kembali ke login</a>
                    <x-primary-button class="rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">
                        Kirim tautan reset
                    </x-primary-button>
                </div>
            </form>
        </div>
    </section>
@endsection
