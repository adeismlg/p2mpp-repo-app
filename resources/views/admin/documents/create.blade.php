@extends('layouts.admin')

@section('title', 'Unggah Dokumen')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Unggah Dokumen Baru</h1>

    <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Judul Dokumen</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div><label class="block text-sm font-medium mb-1">Judul Dokumen (English)</label><input type="text" name="title_en" value="{{ old('title_en') }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="document_category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— Tanpa kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('document_category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>
        <div><label class="block text-sm font-medium mb-1">Deskripsi (English)</label><textarea name="description_en" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description_en') }}</textarea></div>
        <div>
            <label class="block text-sm font-medium mb-1">File (pdf, doc, docx, xls, xlsx, ppt, pptx, zip — maks 20MB)</label>
            <input type="file" name="file" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_public" id="is_public" value="1" checked class="rounded">
            <label for="is_public" class="text-sm">Tampilkan ke publik</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Unggah
        </button>
    </form>
@endsection
