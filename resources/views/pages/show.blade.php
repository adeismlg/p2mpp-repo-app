@extends('layouts.public')

@section('title', $page->localized('title'))

@section('content')
    <div class="page-canvas min-h-[60vh] px-4 py-10 md:py-14">
        <article class="mx-auto max-w-4xl">
            <h1 class="mb-7 text-2xl font-bold tracking-tight text-indigo-950 md:text-3xl">{{ $page->localized('title') }}</h1>
            <div class="page-content text-base leading-8 text-slate-700 lg:text-lg">
                {!! $page->localized('content') !!}
            </div>
        </article>
    </div>
@endsection
