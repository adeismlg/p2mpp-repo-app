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
            <label class="block text-sm font-medium mb-1">Konten</label>
            <textarea name="content" rows="12" data-rich-editor class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('content', $page->content) }}</textarea>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $page->is_published)) class="rounded">
            <label for="is_published" class="text-sm">Terbit</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
