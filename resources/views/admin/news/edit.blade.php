@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Berita</h1>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title', $news->title) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan Singkat</label>
            <input type="text" name="excerpt" value="{{ old('excerpt', $news->excerpt) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Isi Berita</label>
            <textarea name="content" rows="8" class="w-full border rounded-lg px-3 py-2 text-sm" required>{{ old('content', $news->content) }}</textarea>
        </div>
        <div>
            @if ($news->thumbnail)
                <img src="{{ asset('storage/'.$news->thumbnail) }}" class="h-24 rounded-lg mb-2">
            @endif
            <label class="block text-sm font-medium mb-1">Ganti Gambar Sampul (opsional)</label>
            <input type="file" name="thumbnail" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="published" id="published" value="1" @checked(old('published', $news->published_at)) class="rounded">
            <label for="published" class="text-sm">Terbit</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
