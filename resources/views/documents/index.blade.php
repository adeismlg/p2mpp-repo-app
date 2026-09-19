@extends('layouts.app')

@section('title', 'Repository Dokumen')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-1">Repository Dokumen</h1>
    <p class="text-slate-500 mb-8">Cari dan unduh dokumen berdasarkan kategori.</p>

    <form method="GET" action="{{ route('documents.index') }}" class="flex flex-col md:flex-row gap-3 mb-8">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Cari judul atau deskripsi dokumen..."
            class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
        <select name="kategori" class="border rounded-lg px-4 py-2.5 text-sm md:w-56">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($activeCategory === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Cari
        </button>
    </form>

    <div class="grid md:grid-cols-4 gap-8">
        <aside class="md:col-span-1">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Kategori / Folder</h2>
            <ul class="space-y-1 text-sm">
                <li>
                    <a href="{{ route('documents.index') }}"
                       class="block px-3 py-2 rounded-lg {{ ! $activeCategory ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-slate-100' }}">
                        Semua Dokumen
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('documents.index', ['kategori' => $category->slug]) }}"
                           class="block px-3 py-2 rounded-lg {{ $activeCategory === $category->slug ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-slate-100' }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="md:col-span-3">
            @if ($documents->isEmpty())
                <div class="bg-white border rounded-xl p-10 text-center text-slate-500 text-sm">
                    Tidak ada dokumen yang cocok dengan pencarian kamu.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($documents as $document)
                        <div class="bg-white border rounded-xl p-5 flex items-start justify-between gap-4">
                            <div>
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-1">
                                    {{ $document->category?->name ?? 'Umum' }}
                                </span>
                                <a href="{{ route('documents.show', $document) }}" class="block font-semibold hover:text-indigo-700">
                                    {{ $document->title }}
                                </a>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $document->description }}</p>
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ strtoupper($document->file_type) }} &middot; {{ $document->formatted_size }}
                                    &middot; {{ $document->downloads_count }}x diunduh
                                </p>
                            </div>
                            <a href="{{ route('documents.download', $document) }}"
                               class="shrink-0 bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Unduh
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
