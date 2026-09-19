@extends('layouts.admin')

@section('title', 'Halaman Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Halaman Baru</h1>

    <form action="{{ route('admin.pages.store') }}" method="POST" class="bg-white border rounded-xl p-6 lg:p-8 max-w-5xl space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Judul Halaman</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Konten (boleh pakai HTML dasar: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, dst.)</label>
            <textarea name="content" rows="12" data-rich-editor class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('content') }}</textarea>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" id="is_published" value="1" checked class="rounded">
            <label for="is_published" class="text-sm">Terbitkan sekarang</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
