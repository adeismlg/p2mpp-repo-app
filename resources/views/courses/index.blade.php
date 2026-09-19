@extends('layouts.public')

@section('title', 'Pelatihan')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-1">Pelatihan</h1>
    <p class="text-slate-500 mb-8">Daftar program/kursus yang tersedia.</p>

    <div class="grid md:grid-cols-3 gap-5">
        @foreach ($courses as $course)
            <a href="{{ route('courses.show', $course) }}" class="block bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                @if ($course->thumbnail)
                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-40 object-cover">
                @endif
                <div class="p-5">
                    <h3 class="font-semibold mb-1">{{ $course->name }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-3">{{ $course->description }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
