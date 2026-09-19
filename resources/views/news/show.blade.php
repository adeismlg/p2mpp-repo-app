@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('news.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Berita</a>

    <article class="bg-white border rounded-xl overflow-hidden mt-4">
        @if ($news->thumbnail)
            <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="{{ $news->title }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-8">
            <p class="text-xs text-slate-400 mb-2">{{ $news->published_at?->format('d M Y') }}</p>
            <h1 class="text-2xl font-bold mb-4">{{ $news->title }}</h1>
            <div class="prose max-w-none text-slate-700">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>
    </article>
</div>
@endsection
