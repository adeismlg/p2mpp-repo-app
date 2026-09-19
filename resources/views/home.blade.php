@extends('layouts.public')

@section('content')
    <section class="bg-gradient-to-br from-indigo-700 to-indigo-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-20 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Nama Unit / Lembaga Anda</h1>
            <p class="max-w-2xl mx-auto text-indigo-100 mb-8">
                Ganti teks ini dengan deskripsi unit kamu — visi, misi, dan layanan utama.
            </p>
            <a href="{{ route('documents.index') }}" class="inline-block bg-white text-indigo-700 font-semibold px-6 py-3 rounded-lg hover:bg-indigo-50">
                Jelajahi Repository Dokumen
            </a>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Dokumen Terbaru</h2>
            <a href="{{ route('documents.index') }}" class="text-indigo-600 text-sm font-semibold hover:underline">Lihat semua &rarr;</a>
        </div>

        @if ($latestDocuments->isEmpty())
            <p class="text-slate-500 text-sm">Belum ada dokumen yang diunggah.</p>
        @else
            <div class="grid md:grid-cols-3 gap-5">
                @foreach ($latestDocuments as $document)
                    <a href="{{ route('documents.show', $document) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-2">
                            {{ $document->category?->name ?? 'Umum' }}
                        </span>
                        <h3 class="font-semibold mb-1">{{ $document->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $document->description }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-white border-y">
        <div class="max-w-6xl mx-auto px-4 py-16">
            <h2 class="text-xl font-bold mb-6">Pelatihan</h2>
            <div class="grid md:grid-cols-4 gap-5">
                @foreach ($courses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="block border rounded-xl p-5 hover:shadow-md transition">
                        <h3 class="font-semibold mb-1">{{ $course->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-3">{{ $course->description }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-xl font-bold mb-6">Berita Terbaru</h2>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach ($latestNews as $item)
                <a href="{{ route('news.show', $item) }}" class="block bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                    <div class="p-5">
                        <p class="text-xs text-slate-400 mb-2">{{ $item->published_at?->format('d M Y') }}</p>
                        <h3 class="font-semibold mb-1">{{ $item->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $item->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
