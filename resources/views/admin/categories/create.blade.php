@extends('layouts.admin')

@section('title', 'Kategori Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Kategori Baru</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white border rounded-xl p-6 max-w-lg space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi (opsional)</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
