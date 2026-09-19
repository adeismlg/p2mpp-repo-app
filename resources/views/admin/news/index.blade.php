@extends('layouts.admin')

@section('title', 'Berita')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Berita</h1>
        <a href="{{ route('admin.news.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Berita Baru
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
                @forelse ($news as $item)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $item->title }}</td>
                        <td class="px-5 py-3">
                            @if ($item->published_at)
                                <span class="text-green-600 text-xs font-semibold">Terbit {{ $item->published_at->format('d M Y') }}</span>
                            @else
                                <span class="text-slate-400 text-xs font-semibold">Draf</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.news.edit', $item) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-6 text-center text-slate-400">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $news->links() }}</div>
@endsection
