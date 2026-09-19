@extends('layouts.admin')

@section('title', 'Repository Dokumen')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Repository Dokumen</h1>
        <a href="{{ route('admin.documents.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Unggah Dokumen
        </a>
    </div>

    <form method="GET" class="mb-6">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari dokumen..."
               class="w-full max-w-sm border rounded-lg px-4 py-2 text-sm">
    </form>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Ukuran</th>
                    <th class="px-5 py-3">Unduhan</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($documents as $document)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $document->title }}</td>
                        <td class="px-5 py-3">{{ $document->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3">{{ $document->formatted_size }}</td>
                        <td class="px-5 py-3">{{ $document->downloads_count }}</td>
                        <td class="px-5 py-3">
                            @if ($document->is_public)
                                <span class="text-green-600 text-xs font-semibold">Publik</span>
                            @else
                                <span class="text-slate-400 text-xs font-semibold">Tersembunyi</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.documents.edit', $document) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-slate-400">Belum ada dokumen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $documents->links() }}</div>
@endsection
