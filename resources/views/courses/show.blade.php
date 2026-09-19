@extends('layouts.public')

@section('title', $course->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <a href="{{ route('courses.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Pelatihan</a>

    <article class="bg-white border rounded-xl overflow-hidden mt-4 shadow-sm">
        @if ($course->thumbnail)
            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-64 lg:h-80 object-cover">
        @endif
        <div class="p-8 lg:p-12">
            <h1 class="text-2xl lg:text-3xl font-bold mb-5">{{ $course->name }}</h1>
            <p class="text-slate-700 text-base lg:text-lg leading-8 mb-8 max-w-4xl">{{ $course->description }}</p>

            @if (!empty($course->facilities))
                <h2 class="text-lg font-semibold mb-3">Fasilitas</h2>
                <ul class="list-disc list-inside text-slate-600 space-y-2">
                    @foreach ($course->facilities as $facility)
                        <li>{{ $facility }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </article>
</div>
@endsection
