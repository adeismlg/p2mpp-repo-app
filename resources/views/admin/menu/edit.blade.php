@extends('layouts.admin')

@section('title', 'Edit Item Menu')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Item Menu</h1>

    <form action="{{ route('admin.menu.update', $item) }}" method="POST" x-data="{ type: '{{ old('type', $item->type) }}' }" class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Induk Menu</label>
            <select name="parent_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— Menu utama —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $item->parent_id) == $parent->id)>{{ $parent->label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Label</label>
            <input type="text" name="label" value="{{ old('label', $item->label) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Label (English)</label>
            <input type="text" name="label_en" value="{{ old('label_en', $item->label_en) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tipe Tautan</label>
            <select name="type" x-model="type" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="route">Halaman bawaan</option>
                <option value="page">Halaman konten</option>
                <option value="external">URL eksternal</option>
            </select>
        </div>

        <div x-show="type === 'route'">
            <label class="block text-sm font-medium mb-1">Pilih Halaman Bawaan</label>
            <select name="route_name" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach ($routes as $name => $label)
                    <option value="{{ $name }}" @selected(old('route_name', $item->route_name) === $name)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div x-show="type === 'page'">
            <label class="block text-sm font-medium mb-1">Pilih Halaman Konten</label>
            <select name="page_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— pilih halaman —</option>
                @foreach ($pages as $page)
                    <option value="{{ $page->id }}" @selected(old('page_id', $item->page_id) == $page->id)>{{ $page->title }}</option>
                @endforeach
            </select>
        </div>

        <div x-show="type === 'external'">
            <label class="block text-sm font-medium mb-1">URL Tujuan</label>
            <input type="url" name="url" value="{{ old('url', $item->url) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $item->open_in_new_tab)) class="rounded">
            <label for="open_in_new_tab" class="text-sm">Buka di tab baru</label>
        </div>

        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
