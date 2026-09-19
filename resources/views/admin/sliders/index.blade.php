@extends('layouts.admin')

@section('title', 'Slider Beranda')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Slider Beranda</h1>
            <p class="text-sm text-slate-500">Kelola gambar dan konten yang tampil pada slider halaman beranda.</p>
        </div>
        <a href="{{ route('admin.sliders.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">+ Slider Baru</a>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Slider</th>
                    <th class="px-5 py-3">Urutan</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($sliders as $slider)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('storage/'.$slider->image) }}" alt="" class="h-12 w-20 rounded object-cover">
                                <span class="font-medium">{{ $slider->title }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">{{ $slider->order }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ $slider->is_active ? 'text-green-600' : 'text-slate-400' }} text-xs font-semibold">
                                {{ $slider->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="inline" onsubmit="return confirm('Hapus slider ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada slider.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $sliders->links() }}</div>
@endsection
