@extends('layouts.public')

@section('title', 'Profile')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Akun</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ __('Profile') }}</h1>
            <p class="mt-2 text-slate-500">Kelola informasi, password, dan keamanan akun Anda.</p>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if (auth()->user()->isStaff())
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.two-factor-authentication-form')
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
