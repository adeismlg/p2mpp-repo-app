@extends('layouts.public')

@section('title', 'Reset Password')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Bantuan akun</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Buat password baru</h1>
            <p class="mt-4 max-w-2xl text-indigo-100">Gunakan password baru yang kuat untuk mengamankan akun Anda.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="max-w-xl mx-auto rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[30rem]">
            <h2 class="text-3xl font-bold text-slate-900">Password baru</h2>
            <p class="mt-2 text-sm text-slate-500">Lengkapi formulir berikut untuk memperbarui password.</p>

            <form method="POST" action="{{ route('password.store') }}" class="mt-9 space-y-7">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
                    <x-text-input id="email" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
                    <x-text-input id="password" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-700" />
                    <x-text-input id="password_confirmation" class="block mt-2 h-12 w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button class="rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">Simpan password</x-primary-button>
                </div>
            </form>
        </div>
    </section>
@endsection
