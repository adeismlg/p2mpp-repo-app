@extends('layouts.admin')

@section('title', 'Edit Halaman')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Halaman</h1>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="bg-white border rounded-xl p-6 lg:p-8 max-w-5xl space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Judul Halaman</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Judul Halaman (English)</label>
            <input type="text" name="title_en" value="{{ old('title_en', $page->title_en) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        @if ($page->slug === 'akreditasi-program-studi')
            <div class="rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
                Daftar program studi dibuat dari data Akreditasi Prodi. Kelola informasi dan sertifikat per program melalui menu Akreditasi Prodi.
            </div>
        @else
            <div>
                <label class="block text-sm font-medium mb-1">Konten</label>
                <p class="mb-2 text-xs text-slate-500">Gunakan menu Blok untuk panel dan tata letak. Sisipkan gambar melalui tombol Gambar; format JPG, PNG, atau WebP, maksimal 25 MB.</p>
                <textarea name="content" rows="12" data-rich-editor data-rich-editor-blocks data-rich-editor-css="{{ Vite::asset('resources/css/app.css') }}" data-rich-editor-upload-url="{{ route('admin.editor-images.store') }}" data-rich-editor-csrf="{{ csrf_token() }}" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('content', $page->content) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Konten (English)</label>
                <textarea name="content_en" rows="12" data-rich-editor data-rich-editor-blocks data-rich-editor-css="{{ Vite::asset('resources/css/app.css') }}" data-rich-editor-upload-url="{{ route('admin.editor-images.store') }}" data-rich-editor-csrf="{{ csrf_token() }}" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('content_en', $page->content_en) }}</textarea>
            </div>
        @endif
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $page->is_published)) class="rounded">
            <label for="is_published" class="text-sm">Terbit</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
