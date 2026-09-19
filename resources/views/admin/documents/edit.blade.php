@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Dokumen</h1>

    <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Judul Dokumen</label>
            <input type="text" name="title" value="{{ old('title', $document->title) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="document_category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— Tanpa kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('document_category_id', $document->document_category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description', $document->description) }}</textarea>
        </div>
        <div>
            <p class="text-sm text-slate-500 mb-1">File saat ini: <strong>{{ $document->file_name }}</strong> ({{ $document->formatted_size }})</p>
            <label class="block text-sm font-medium mb-1">Ganti file (opsional)</label>
            <input type="file" name="file" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_public" id="is_public" value="1" @checked(old('is_public', $document->is_public)) class="rounded">
            <label for="is_public" class="text-sm">Tampilkan ke publik</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
