@extends('layouts.public')

@section('title', $document->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('documents.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Repository Dokumen</a>

    <div class="bg-white border rounded-xl p-8 mt-4">
        <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-2">
            {{ $document->category?->name ?? 'Umum' }}
        </span>
        <h1 class="text-2xl font-bold mb-3">{{ $document->title }}</h1>
        <p class="text-slate-600 mb-6">{{ $document->description }}</p>

        <dl class="grid grid-cols-2 gap-4 text-sm mb-8 border-t border-b py-4">
            <div>
                <dt class="text-slate-400">Tipe File</dt>
                <dd class="font-medium">{{ strtoupper($document->file_type) }}</dd>
            </div>
            <div>
                <dt class="text-slate-400">Ukuran File</dt>
                <dd class="font-medium">{{ $document->formatted_size }}</dd>
            </div>
            <div>
                <dt class="text-slate-400">Diunggah</dt>
                <dd class="font-medium">{{ $document->created_at->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-slate-400">Jumlah Unduhan</dt>
                <dd class="font-medium">{{ $document->downloads_count }}x</dd>
            </div>
        </dl>

        <a href="{{ route('documents.download', $document) }}"
           class="inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-indigo-700">
            Unduh Dokumen
        </a>
    </div>
</div>
@endsection
