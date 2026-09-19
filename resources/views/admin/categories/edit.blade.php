@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Kategori</h1>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="bg-white border rounded-xl p-6 max-w-lg space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div><label class="block text-sm font-medium mb-1">Nama Kategori (English)</label><input type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi (opsional)</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description', $category->description) }}</textarea>
        </div>
        <div><label class="block text-sm font-medium mb-1">Deskripsi (English)</label><textarea name="description_en" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description_en', $category->description_en) }}</textarea></div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
