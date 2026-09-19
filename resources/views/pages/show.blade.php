@extends('layouts.public')

@section('title', $page->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <article class="bg-white border rounded-xl p-8 lg:p-12 shadow-sm">
        <h1 class="text-2xl lg:text-3xl font-bold mb-8">{{ $page->title }}</h1>
        <div class="prose max-w-4xl text-slate-700 text-base lg:text-lg leading-8">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection
