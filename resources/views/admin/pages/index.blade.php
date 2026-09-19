@extends('layouts.admin')

@section('title', 'Halaman Konten')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Halaman Konten</h1>
            <p class="text-sm text-slate-500">Halaman statis seperti Visi Misi, Kebijakan Mutu, dst. Hubungkan ke menu lewat menu Admin &rarr; Menu Navigasi.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Halaman Baru
        </a>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pages as $page)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $page->title }}</td>
                        <td class="px-5 py-3">
                            @if ($page->is_published)
                                <span class="text-green-600 text-xs font-semibold">Terbit</span>
                            @else
                                <span class="text-slate-400 text-xs font-semibold">Draf</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('pages.show', $page) }}" target="_blank" class="text-slate-500 hover:underline">Lihat</a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus halaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-6 text-center text-slate-400">Belum ada halaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $pages->links() }}</div>
@endsection
