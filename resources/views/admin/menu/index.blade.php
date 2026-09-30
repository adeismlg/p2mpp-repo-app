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
                    <div class="inline-flex items-center gap-1">
                        <a href="{{ route('admin.menu.create', ['parent' => $item->id]) }}" title="Tambah sub-menu"
                           class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.menu.edit', $item) }}" title="Edit"
                           class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5v4.875c0 .621-.504 1.125-1.125 1.125H5.625a1.125 1.125 0 01-1.125-1.125V6.75c0-.621.504-1.125 1.125-1.125h4.875" />
                            </svg>
                        </a>
                        <form action="{{ route('admin.menu.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Hapus item menu ini beserta sub-menunya?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
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
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.menu.edit', $child) }}" title="Edit"
                                       class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5v4.875c0 .621-.504 1.125-1.125 1.125H5.625a1.125 1.125 0 01-1.125-1.125V6.75c0-.621.504-1.125 1.125-1.125h4.875" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.menu.destroy', $child) }}" method="POST"
                                          onsubmit="return confirm('Hapus sub-menu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
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
