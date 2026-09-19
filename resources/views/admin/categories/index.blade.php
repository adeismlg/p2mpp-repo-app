@extends('layouts.admin')

@section('title', 'Kategori Dokumen')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Kategori / Folder Dokumen</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Kategori Baru
        </a>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Jumlah Dokumen</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-5 py-3">{{ $category->documents_count }}</td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-6 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $categories->links() }}</div>
@endsection
