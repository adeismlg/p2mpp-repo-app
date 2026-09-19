@extends('layouts.admin')

@section('title', 'Program')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Program</h1>
        <a href="{{ route('admin.courses.create') }}" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Program Baru
        </a>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($courses as $course)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $course->name }}</td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus program ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-5 py-6 text-center text-slate-400">Belum ada program.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $courses->links() }}</div>
@endsection
