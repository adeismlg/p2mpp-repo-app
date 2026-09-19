@extends('layouts.public')

@section('title', $news->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <a href="{{ route('news.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Berita</a>

    <article class="bg-white border rounded-xl overflow-hidden mt-4 shadow-sm">
        @if ($news->thumbnail)
            <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="{{ $news->title }}" class="w-full h-64 lg:h-80 object-cover">
        @endif
        <div class="p-8 lg:p-12">
            <p class="text-xs text-slate-400 mb-2">{{ $news->published_at?->format('d M Y') }}</p>
            <h1 class="text-2xl lg:text-3xl font-bold mb-6">{{ $news->title }}</h1>
            <div class="prose max-w-4xl text-slate-700 text-base lg:text-lg leading-8">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>
    </article>
</div>
@endsection
