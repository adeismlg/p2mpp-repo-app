@extends('layouts.admin')

@section('title', 'Berita Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tulis Berita Baru</h1>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 lg:p-8 max-w-5xl space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div><label class="block text-sm font-medium mb-1">Judul (English)</label><input type="text" name="title_en" value="{{ old('title_en') }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan Singkat</label>
            <input type="text" name="excerpt" value="{{ old('excerpt') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div><label class="block text-sm font-medium mb-1">Ringkasan Singkat (English)</label><input type="text" name="excerpt_en" value="{{ old('excerpt_en') }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        <div>
            <label class="block text-sm font-medium mb-1">Isi Berita</label>
            <textarea name="content" rows="8" data-rich-editor class="w-full border rounded-lg px-3 py-2 text-sm" required>{{ old('content') }}</textarea>
        </div>
        <div><label class="block text-sm font-medium mb-1">Isi Berita (English)</label><textarea name="content_en" rows="8" data-rich-editor class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('content_en') }}</textarea></div>
        <div>
            <label class="block text-sm font-medium mb-1">Gambar Sampul (opsional)</label>
            <input type="file" name="thumbnail" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="published" id="published" value="1" class="rounded">
            <label for="published" class="text-sm">Terbitkan sekarang</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
