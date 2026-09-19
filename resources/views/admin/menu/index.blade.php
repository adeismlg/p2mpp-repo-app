@extends('layouts.admin')

@section('title', 'Menu Navigasi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Menu Navigasi</h1>
            <p class="text-sm text-slate-500">Atur menu di navbar situs publik. Item tanpa parent tampil sebagai menu utama; item dengan parent tampil sebagai dropdown.</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Item Menu
        </a>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden divide-y">
        @forelse ($items as $item)
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col">
                            <form action="{{ route('admin.menu.up', $item) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="text-slate-400 hover:text-slate-700 text-xs leading-none">▲</button>
                            </form>
                            <form action="{{ route('admin.menu.down', $item) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="text-slate-400 hover:text-slate-700 text-xs leading-none">▼</button>
                            </form>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $item->label }}</p>
                            <p class="text-xs text-slate-400">
                                @switch($item->type)
                                    @case('route') Rute internal: {{ $item->route_name }} @break
                                    @case('page') Halaman: {{ $item->page?->title ?? '—' }} @break
                                    @default URL: {{ $item->url }}
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div class="space-x-3 text-sm">
                        <a href="{{ route('admin.menu.create', ['parent' => $item->id]) }}" class="text-slate-500 hover:underline">+ Sub-menu</a>
                        <a href="{{ route('admin.menu.edit', $item) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus item menu ini beserta sub-menunya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>

                @if ($item->children->isNotEmpty())
                    <div class="mt-3 ml-10 pl-4 border-l space-y-2">
                        @foreach ($item->children as $child)
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <p class="font-medium">{{ $child->label }}</p>
                                    <p class="text-xs text-slate-400">
                                        @switch($child->type)
                                            @case('route') Rute internal: {{ $child->route_name }} @break
                                            @case('page') Halaman: {{ $child->page?->title ?? '—' }} @break
                                            @default URL: {{ $child->url }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="space-x-3">
                                    <a href="{{ route('admin.menu.edit', $child) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.menu.destroy', $child) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus sub-menu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="p-6 text-center text-slate-400 text-sm">Belum ada menu. Klik "+ Item Menu" untuk mulai.</p>
        @endforelse
    </div>
@endsection
