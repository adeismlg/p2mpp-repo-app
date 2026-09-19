@extends('layouts.public')

@section('title', 'Verifikasi Email')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200">Aktivasi akun</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Verifikasi email Anda</h1>
            <p class="mt-4 max-w-2xl text-indigo-100">Satu langkah lagi untuk mengaktifkan seluruh akses akun.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="max-w-xl mx-auto rounded-2xl bg-white border border-slate-200 p-6 sm:p-10 shadow-sm lg:min-h-[26rem]">
            <h2 class="text-3xl font-bold text-slate-900">Periksa inbox Anda</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Kami telah mengirimkan tautan verifikasi ke alamat email Anda. Klik tautan tersebut untuk melanjutkan.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    Tautan verifikasi baru telah dikirim ke alamat email Anda.
                </div>
            @endif

            <div class="mt-10 flex flex-wrap items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="rounded-lg py-3.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">Kirim ulang email</x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-500 hover:text-slate-800 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </section>
@endsection
