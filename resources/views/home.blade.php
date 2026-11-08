@extends('layouts.public')

@section('content')
    <section
        x-data="{ active: 0, slides: @js($sliders->map(fn ($slider) => ['id' => $slider->id, 'image' => $slider->image, 'title' => $slider->localized('title'), 'subtitle' => $slider->localized('subtitle'), 'link_url' => $slider->link_url, 'button_label' => $slider->localized('button_label')])->values()) }"
        x-init="setInterval(() => active = slides.length ? (active + 1) % slides.length : 0, 6000)"
        class="relative isolate overflow-hidden bg-gradient-to-br from-indigo-50 via-blue-50 to-white"
    >
        <div aria-hidden="true" class="pointer-events-none absolute -right-24 -top-32 h-96 w-96 rounded-full bg-orange-200/30 blur-3xl"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-48 -left-20 h-96 w-96 rounded-full bg-indigo-200/40 blur-3xl"></div>

        <div class="relative mx-auto grid min-h-[38rem] max-w-7xl items-center gap-10 px-5 py-14 sm:px-8 lg:grid-cols-2 lg:gap-16 lg:py-20">
            <div class="relative z-10">
                <p class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-indigo-900 shadow-sm ring-1 ring-indigo-100 sm:text-sm">
                    <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                    {{ __('public.hero_tagline') }}
                </p>

                <template x-if="slides.length > 0">
                    <div>
                        <h1 class="mb-5 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-indigo-950 sm:text-5xl lg:text-6xl" x-text="slides[active].title"></h1>
                        <p x-show="slides[active].subtitle" class="mb-8 max-w-xl text-base leading-8 text-slate-600 sm:text-lg" x-text="slides[active].subtitle"></p>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a :href="slides[active].link_url || '{{ route('documents.index') }}'"
                               class="inline-flex min-h-14 items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-400 px-7 py-3 text-center font-bold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:shadow-xl"
                               x-text="slides[active].button_label || '{{ __('public.document_repository') }}'"></a>
                            <a href="{{ route('courses.index') }}" class="inline-flex min-h-14 items-center justify-center rounded-xl border-2 border-indigo-500 bg-white/80 px-7 py-3 text-center font-bold text-indigo-700 transition hover:bg-indigo-50">
                                {{ __('public.training') }}
                            </a>
                        </div>
                    </div>
                </template>

                <template x-if="slides.length === 0">
                    <div>
                        <h1 class="mb-5 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-indigo-950 sm:text-5xl lg:text-6xl">
                            {{ __('public.hero_title') }}
                        </h1>
                        <p class="mb-8 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">{{ __('public.hero_description') }}</p>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('documents.index') }}" class="inline-flex min-h-14 items-center justify-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-400 px-7 py-3 text-center font-bold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                                {{ __('public.document_repository') }}
                            </a>
                            <a href="{{ route('courses.index') }}" class="inline-flex min-h-14 items-center justify-center rounded-xl border-2 border-indigo-500 bg-white/80 px-7 py-3 text-center font-bold text-indigo-700 transition hover:bg-indigo-50">
                                {{ __('public.training') }}
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div class="relative mx-auto w-full max-w-xl">
                <div aria-hidden="true" class="absolute -inset-4 rotate-3 rounded-[2rem] bg-gradient-to-br from-orange-200/70 to-indigo-200/70"></div>
                <div class="relative aspect-[4/3] overflow-hidden rounded-[1.75rem] border-8 border-white bg-white shadow-2xl shadow-indigo-950/10">
                    <template x-for="(slide, index) in slides" :key="slide.id">
                        <div x-show="active === index" x-transition.opacity.duration.700ms class="absolute inset-0">
                            <img :src="'{{ asset('storage') }}/' + slide.image" :alt="slide.title" class="h-full w-full object-contain">
                        </div>
                    </template>
                    <div x-show="slides.length === 0" class="absolute inset-0 flex flex-col items-center justify-center gap-5 bg-gradient-to-br from-white via-indigo-50 to-blue-100 p-8 text-center">
                        <img src="{{ asset('images/logo-polinema.png') }}" alt="Logo Politeknik Negeri Malang" class="h-32 w-32 object-contain drop-shadow-sm">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.16em] text-indigo-500">Politeknik Negeri Malang</p>
                            <p class="mt-2 text-xl font-extrabold text-indigo-950">{{ __('public.hero_visual_title') }}</p>
                        </div>
                    </div>
                </div>
                <div x-show="slides.length > 1" class="absolute bottom-4 right-4 flex gap-2">
                    <button type="button" @click="active = (active - 1 + slides.length) % slides.length" aria-label="{{ __('public.previous_slide') }}" class="rounded-full bg-white/90 p-2.5 text-indigo-900 shadow-sm transition hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button type="button" @click="active = (active + 1) % slides.length" aria-label="{{ __('public.next_slide') }}" class="rounded-full bg-white/90 p-2.5 text-indigo-900 shadow-sm transition hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-indigo-100 bg-white">
        <div class="mx-auto grid max-w-7xl gap-5 px-5 py-10 sm:px-8 md:grid-cols-2">
            <a href="{{ route('pages.show', 'visi-misi-polinema') }}" class="group flex items-start gap-4 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 transition group-hover:bg-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </span>
                <span>
                    <span class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-orange-500">{{ __('public.polinema_profile') }}</span>
                    <span class="block text-lg font-bold text-indigo-950">{{ __('public.vision_mission') }}</span>
                    <span class="mt-1 block text-sm leading-6 text-slate-500">{{ __('public.polinema_vision_summary') }}</span>
                </span>
            </a>
            <a href="{{ route('pages.show', 'jurusan-polinema') }}" class="group flex items-start gap-4 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600 transition group-hover:bg-orange-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4m-4 0h.01M9 11h.01M9 15h.01M9 19h.01m8-6h.01m-.01 4h.01" /></svg>
                </span>
                <span>
                    <span class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-orange-500">{{ __('public.academic_information') }}</span>
                    <span class="block text-lg font-bold text-indigo-950">{{ __('public.departments_programs') }}</span>
                    <span class="mt-1 block text-sm leading-6 text-slate-500">{{ __('public.departments_summary') }}</span>
                </span>
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8">
        <div class="mb-7 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-orange-500">{{ __('public.document_repository') }}</p>
                <h2 class="text-2xl font-bold text-indigo-950">{{ __('public.latest_documents') }}</h2>
            </div>
            <a href="{{ route('documents.index') }}" class="shrink-0 text-sm font-semibold text-indigo-600 transition hover:text-orange-500">{{ __('public.view_all') }}</a>
        </div>

        @if ($latestDocuments->isEmpty())
            <p class="rounded-2xl border border-dashed border-indigo-200 bg-white px-6 py-8 text-sm text-slate-500">{{ __('public.no_documents') }}</p>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($latestDocuments as $document)
                    <a href="{{ route('documents.show', $document) }}" class="group block rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                        <span class="mb-3 inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                            {{ $document->category?->localized('name') ?? __('public.general') }}
                        </span>
                        <h3 class="mb-2 font-semibold text-indigo-950 transition group-hover:text-indigo-700">{{ $document->localized('title') }}</h3>
                        <p class="line-clamp-2 text-sm leading-6 text-slate-500">{{ $document->localized('description') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="border-y border-indigo-100 bg-gradient-to-br from-indigo-50/80 to-white">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8">
            <div class="mb-7">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-orange-500">{{ __('public.learning_development') }}</p>
                <h2 class="text-2xl font-bold text-indigo-950">{{ __('public.training') }}</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($courses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="group block rounded-2xl border border-white bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </span>
                        <h3 class="mb-2 font-semibold text-indigo-950 transition group-hover:text-indigo-700">{{ $course->localized('name') }}</h3>
                        <p class="line-clamp-3 text-sm leading-6 text-slate-500">{{ $course->localized('description') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8">
        <div class="mb-7">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-orange-500">{{ __('public.p2mpp_news') }}</p>
            <h2 class="text-2xl font-bold text-indigo-950">{{ __('public.latest_news') }}</h2>
        </div>
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($latestNews as $item)
                <a href="{{ route('news.show', $item) }}" class="group block overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <p class="mb-2 text-xs font-semibold text-orange-500">{{ $item->published_at?->format('d M Y') }}</p>
                        <h3 class="mb-2 font-semibold text-indigo-950 transition group-hover:text-indigo-700">{{ $item->localized('title') }}</h3>
                        <p class="line-clamp-2 text-sm leading-6 text-slate-500">{{ $item->localized('excerpt') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
