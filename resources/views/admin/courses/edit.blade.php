@extends('layouts.admin')

@section('title', 'Edit Pelatihan')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Pelatihan</h1>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Nama Pelatihan</label>
            <input type="text" name="name" value="{{ old('name', $course->name) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description', $course->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Fasilitas (satu poin per baris)</label>
            <textarea name="facilities" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('facilities', implode("\n", $course->facilities ?? [])) }}</textarea>
        </div>
        <div>
            @if ($course->thumbnail)
                <img src="{{ asset('storage/'.$course->thumbnail) }}" class="h-24 rounded-lg mb-2">
            @endif
            <label class="block text-sm font-medium mb-1">Ganti Gambar (opsional)</label>
            <input type="file" name="thumbnail" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
