@extends('layouts.admin')

@section('title', 'Item Menu Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Item Menu Baru</h1>

    <form action="{{ route('admin.menu.store') }}" method="POST" x-data="{ type: '{{ old('type', 'route') }}' }" class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Induk Menu (opsional — kosongkan untuk menu utama)</label>
            <select name="parent_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— Menu utama —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', request('parent')) == $parent->id)>{{ $parent->label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Label (teks yang tampil di menu)</label>
            <input type="text" name="label" value="{{ old('label') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tipe Tautan</label>
            <select name="type" x-model="type" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="route">Halaman bawaan (Beranda, Berita, dll)</option>
                <option value="page">Halaman konten (dibuat lewat Admin &rarr; Halaman)</option>
                <option value="external">URL eksternal (sistem lain, mis. SIJAMU)</option>
            </select>
        </div>

        <div x-show="type === 'route'">
            <label class="block text-sm font-medium mb-1">Pilih Halaman Bawaan</label>
            <select name="route_name" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach ($routes as $name => $label)
                    <option value="{{ $name }}" @selected(old('route_name') === $name)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div x-show="type === 'page'">
            <label class="block text-sm font-medium mb-1">Pilih Halaman Konten</label>
            <select name="page_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">— pilih halaman —</option>
                @foreach ($pages as $page)
                    <option value="{{ $page->id }}" @selected(old('page_id') == $page->id)>{{ $page->title }}</option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-1">Belum ada halaman yang cocok? <a href="{{ route('admin.pages.create') }}" class="text-indigo-600 hover:underline">Buat halaman baru</a> dulu.</p>
        </div>

        <div x-show="type === 'external'">
            <label class="block text-sm font-medium mb-1">URL Tujuan</label>
            <input type="url" name="url" value="{{ old('url') }}" placeholder="https://..." class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" value="1" @checked(old('open_in_new_tab')) class="rounded">
            <label for="open_in_new_tab" class="text-sm">Buka di tab baru</label>
        </div>

        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
