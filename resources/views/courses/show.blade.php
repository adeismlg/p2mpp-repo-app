@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('courses.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Program</a>

    <article class="bg-white border rounded-xl overflow-hidden mt-4">
        @if ($course->thumbnail)
            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-8">
            <h1 class="text-2xl font-bold mb-4">{{ $course->name }}</h1>
            <p class="text-slate-700 mb-6">{{ $course->description }}</p>

            @if (!empty($course->facilities))
                <h2 class="font-semibold mb-2">Fasilitas</h2>
                <ul class="list-disc list-inside text-slate-600 space-y-1">
                    @foreach ($course->facilities as $facility)
                        <li>{{ $facility }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </article>
</div>
@endsection
