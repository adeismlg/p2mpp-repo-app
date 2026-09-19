@extends('layouts.app')

@section('title', 'Berita')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-1">Berita</h1>
    <p class="text-slate-500 mb-8">Informasi dan kegiatan terbaru.</p>

    @if ($news->isEmpty())
        <div class="bg-white border rounded-xl p-10 text-center text-slate-500 text-sm">
            Belum ada berita yang dipublikasikan.
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-5">
            @foreach ($news as $item)
                <a href="{{ route('news.show', $item) }}" class="block bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                    @if ($item->thumbnail)
                        <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-5">
                        <p class="text-xs text-slate-400 mb-2">{{ $item->published_at?->format('d M Y') }}</p>
                        <h3 class="font-semibold mb-1">{{ $item->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $item->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $news->links() }}</div>
    @endif
</div>
@endsection
