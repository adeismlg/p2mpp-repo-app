@extends('layouts.admin')

@section('title', 'Pelatihan Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Pelatihan Baru</h1>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama Pelatihan</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Fasilitas (satu poin per baris)</label>
            <textarea name="facilities" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Modul belajar&#10;Simulasi ujian&#10;Sertifikat kelulusan">{{ old('facilities') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Gambar (opsional)</label>
            <input type="file" name="thumbnail" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
