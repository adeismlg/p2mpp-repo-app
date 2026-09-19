@extends('layouts.public')

@section('content')
    <section
        x-data="{ active: 0, slides: @js($sliders->map(fn ($slider) => ['id' => $slider->id, 'image' => $slider->image, 'title' => $slider->localized('title'), 'subtitle' => $slider->localized('subtitle'), 'link_url' => $slider->link_url, 'button_label' => $slider->localized('button_label')])->values()) }"
        x-init="setInterval(() => active = slides.length ? (active + 1) % slides.length : 0, 6000)"
        class="relative isolate overflow-hidden bg-slate-950 text-white"
    >
        <div class="absolute inset-0">
            <template x-for="(slide, index) in slides" :key="slide.id">
                <div x-show="active === index" x-transition.opacity.duration.700ms class="absolute inset-0">
                    <img :src="'{{ asset('storage') }}/' + slide.image" :alt="slide.title" class="h-full w-full object-cover">
                </div>
            </template>
            <div x-show="slides.length === 0" class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-indigo-700 to-indigo-500"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-indigo-950/70 to-indigo-900/20"></div>
        </div>

        <div class="relative mx-auto flex min-h-[30rem] max-w-6xl items-center px-4 py-16 md:min-h-[34rem]">
            <div class="max-w-2xl">
                <template x-if="slides.length > 0">
                    <div>
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-200">P2MPP Polinema</p>
                        <h1 class="mb-5 text-3xl font-extrabold leading-tight md:text-5xl" x-text="slides[active].title"></h1>
                        <p x-show="slides[active].subtitle" class="mb-8 max-w-xl text-base leading-7 text-indigo-100 md:text-lg" x-text="slides[active].subtitle"></p>
                        <a x-show="slides[active].link_url" :href="slides[active].link_url" class="inline-flex items-center rounded-lg bg-white px-6 py-3 font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50" x-text="slides[active].button_label || '{{ __('public.document_repository') }}'"></a>
                    </div>
                </template>
                <template x-if="slides.length === 0">
                    <div>
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-200">P2MPP Polinema</p>
                        <h1 class="mb-5 text-3xl font-extrabold leading-tight md:text-5xl">{{ __('public.hero_title') }}</h1>
                        <p class="mb-8 max-w-xl text-base leading-7 text-indigo-100 md:text-lg">{{ __('public.hero_description') }}</p>
                        <a href="{{ route('documents.index') }}" class="inline-flex items-center rounded-lg bg-white px-6 py-3 font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">{{ __('public.document_repository') }}</a>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="slides.length > 0" class="absolute inset-x-0 bottom-6 z-10 flex items-center justify-between px-4 md:px-8">
            <div class="flex gap-2">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <button type="button" @click="active = index" :aria-label="'{{ __('public.slide') }} ' + (index + 1)" :aria-current="active === index ? 'true' : 'false'" :class="active === index ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'" class="h-2 rounded-full transition-all"></button>
                </template>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="active = (active - 1 + slides.length) % slides.length" aria-label="{{ __('public.previous_slide') }}" class="rounded-full bg-black/25 p-2 text-white backdrop-blur hover:bg-black/45">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button type="button" @click="active = (active + 1) % slides.length" aria-label="{{ __('public.next_slide') }}" class="rounded-full bg-black/25 p-2 text-white backdrop-blur hover:bg-black/45">
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
