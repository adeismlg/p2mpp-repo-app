@extends('layouts.public')

@section('content')
    <section
        x-data="{ active: 0, slides: 3 }"
        x-init="setInterval(() => active = (active + 1) % slides, 6000)"
        class="relative isolate overflow-hidden bg-slate-950 text-white"
    >
        <div class="absolute inset-0">
            <div x-show="active === 0" x-transition.opacity.duration.700ms class="absolute inset-0">
                <img src="{{ asset('images/home-slider/kegiatan-p2mpp.jpeg') }}" alt="Kegiatan P2MPP" class="h-full w-full object-cover">
            </div>
            <div x-show="active === 1" x-transition.opacity.duration.700ms class="absolute inset-0">
                <img src="{{ asset('images/home-slider/kepuasan-mahasiswa-2025.png') }}" alt="Laporan kepuasan mahasiswa tahun 2025" class="h-full w-full object-cover">
            </div>
            <div x-show="active === 2" x-transition.opacity.duration.700ms class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-indigo-700 to-indigo-500"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-indigo-950/70 to-indigo-900/20"></div>
        </div>

        <div class="relative mx-auto flex min-h-[30rem] max-w-6xl items-center px-4 py-16 md:min-h-[34rem]">
            <div class="max-w-2xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-200">P2MPP Polinema</p>
                <h1 class="mb-5 text-3xl font-extrabold leading-tight md:text-5xl">
                    {{ __('public.hero_title') }}
                </h1>
                <p class="mb-8 max-w-xl text-base leading-7 text-indigo-100 md:text-lg">
                    {{ __('public.hero_description') }}
                </p>
                <a href="{{ route('documents.index') }}" class="inline-flex items-center rounded-lg bg-white px-6 py-3 font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">
                    {{ __('public.document_repository') }}
                </a>
            </div>
        </div>

        <div class="absolute inset-x-0 bottom-6 z-10 flex items-center justify-between px-4 md:px-8">
            <div class="flex gap-2">
                <template x-for="index in slides" :key="index">
                    <button
                        type="button"
                        @click="active = index - 1"
                        :aria-label="'{{ __('public.slide') }} ' + index"
                        :aria-current="active === index - 1 ? 'true' : 'false'"
                        :class="active === index - 1 ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                        class="h-2 rounded-full transition-all"
                    ></button>
                </template>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="active = (active - 1 + slides) % slides" aria-label="{{ __('public.previous_slide') }}" class="rounded-full bg-black/25 p-2 text-white backdrop-blur hover:bg-black/45">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button type="button" @click="active = (active + 1) % slides" aria-label="{{ __('public.next_slide') }}" class="rounded-full bg-black/25 p-2 text-white backdrop-blur hover:bg-black/45">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">{{ __('public.latest_documents') }}</h2>
            <a href="{{ route('documents.index') }}" class="text-indigo-600 text-sm font-semibold hover:underline">{{ __('public.view_all') }}</a>
        </div>

        @if ($latestDocuments->isEmpty())
            <p class="text-slate-500 text-sm">{{ __('public.no_documents') }}</p>
        @else
            <div class="grid md:grid-cols-3 gap-5">
                @foreach ($latestDocuments as $document)
                    <a href="{{ route('documents.show', $document) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-2">
                            {{ $document->category?->localized('name') ?? __('public.general') }}
                        </span>
                        <h3 class="font-semibold mb-1">{{ $document->localized('title') }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $document->localized('description') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-white border-y">
        <div class="max-w-6xl mx-auto px-4 py-16">
            <h2 class="text-xl font-bold mb-6">{{ __('public.training') }}</h2>
            <div class="grid md:grid-cols-4 gap-5">
                @foreach ($courses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="block border rounded-xl p-5 hover:shadow-md transition">
                        <h3 class="font-semibold mb-1">{{ $course->localized('name') }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-3">{{ $course->localized('description') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-xl font-bold mb-6">{{ __('public.latest_news') }}</h2>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach ($latestNews as $item)
                <a href="{{ route('news.show', $item) }}" class="block bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                    <div class="p-5">
                        <p class="text-xs text-slate-400 mb-2">{{ $item->published_at?->format('d M Y') }}</p>
                        <h3 class="font-semibold mb-1">{{ $item->localized('title') }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $item->localized('excerpt') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
