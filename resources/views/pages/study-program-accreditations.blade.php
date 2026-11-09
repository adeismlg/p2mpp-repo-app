@extends('layouts.public')

@section('title', $page->localized('title'))

@section('content')
    <section class="page-canvas min-h-[60vh] px-4 py-10 md:py-14">
        <div class="mx-auto max-w-6xl">
            @php($isEnglish = app()->getLocale() === 'en')
            <h1 class="mb-3 text-2xl font-bold tracking-tight text-indigo-950 md:text-3xl">{{ $page->localized('title') }}</h1>
            <p class="mb-8 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                {{ $isEnglish
                    ? 'Study programs with current accreditation are listed below. Certificates can be downloaded when available.'
                    : 'Daftar program studi yang masa akreditasinya masih berlaku. Sertifikat dapat diunduh setelah tersedia.' }}
            </p>

            @if ($accreditations->isEmpty())
                <div class="rounded-xl border bg-white px-6 py-10 text-center text-sm text-slate-500">
                    {{ $isEnglish ? 'No currently valid program accreditation records are available.' : 'Belum ada data akreditasi program studi yang masih berlaku.' }}
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border bg-white">
                    <table class="w-full min-w-[48rem] text-left text-sm">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="px-5 py-3">{{ $isEnglish ? 'Study Program' : 'Program Studi' }}</th>
                                <th class="px-5 py-3">{{ $isEnglish ? 'Level' : 'Jenjang' }}</th>
                                <th class="px-5 py-3">{{ $isEnglish ? 'Rating' : 'Peringkat' }}</th>
                                <th class="px-5 py-3">{{ $isEnglish ? 'Valid until' : 'Berlaku sampai' }}</th>
                                <th class="px-5 py-3">{{ $isEnglish ? 'Certificate' : 'Sertifikat' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($accreditations as $accreditation)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">{{ app()->getLocale() === 'en' && $accreditation->name_en ? $accreditation->name_en : $accreditation->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">SK {{ $accreditation->decree_number }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ $accreditation->study_level }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                            {{ $accreditation->rating }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ $accreditation->valid_until->format('d M Y') }}</td>
                                    <td class="px-5 py-4">
                                        @if ($accreditation->certificate_path)
                                            <a href="{{ route('accreditations.study-program.download', $accreditation) }}" class="font-semibold text-indigo-700 hover:text-orange-600 hover:underline">
                                                {{ $isEnglish ? 'Download certificate' : 'Unduh sertifikat' }}
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">{{ $isEnglish ? 'Not available yet' : 'Belum tersedia' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
