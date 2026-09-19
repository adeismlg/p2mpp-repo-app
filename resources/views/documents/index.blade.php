@extends('layouts.public')

@section('title', __('public.document_repository'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-1">{{ __('public.document_repository') }}</h1>
    <p class="text-slate-500 mb-8">{{ __('public.search_documents') }}</p>

    <form method="GET" action="{{ route('documents.index') }}" class="flex flex-col md:flex-row gap-3 mb-8">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="{{ __('public.search_documents') }}"
            class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
        <select name="kategori" class="border rounded-lg px-4 py-2.5 text-sm md:w-56">
            <option value="">{{ __('public.all_categories') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($activeCategory === $category->slug)>
                    {{ $category->localized('name') }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            {{ __('public.search') }}
        </button>
    </form>

    <div class="grid md:grid-cols-4 gap-8">
        <aside class="md:col-span-1">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('public.category_folder') }}</h2>
            <ul class="space-y-1 text-sm">
                <li>
                    <a href="{{ route('documents.index') }}"
                       class="block px-3 py-2 rounded-lg {{ ! $activeCategory ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-slate-100' }}">
                        {{ __('public.all_documents') }}
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('documents.index', ['kategori' => $category->slug]) }}"
                           class="block px-3 py-2 rounded-lg {{ $activeCategory === $category->slug ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-slate-100' }}">
                            {{ $category->localized('name') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="md:col-span-3">
            @if ($documents->isEmpty())
                <div class="bg-white border rounded-xl p-10 text-center text-slate-500 text-sm">
                    {{ __('public.no_matching_documents') }}
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($documents as $document)
                        <div class="bg-white border rounded-xl p-5 flex items-start justify-between gap-4">
                            <div>
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-1">
                                    {{ $document->category?->localized('name') ?? __('public.general') }}
                                </span>
                                <a href="{{ route('documents.show', $document) }}" class="block font-semibold hover:text-indigo-700">
                                    {{ $document->localized('title') }}
                                </a>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $document->localized('description') }}</p>
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ strtoupper($document->file_type) }} &middot; {{ $document->formatted_size }}
                                    &middot; {{ $document->downloads_count }}x diunduh
                                </p>
                            </div>
                            <a href="{{ route('documents.download', $document) }}"
                               class="shrink-0 bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
                                {{ __('public.download') }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
