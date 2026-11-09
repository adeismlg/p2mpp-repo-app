@extends('layouts.admin')

@section('title', 'Akreditasi Program Studi')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Akreditasi Program Studi</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data akreditasi dan unggah sertifikat untuk setiap program studi.</p>
        </div>
        <a href="{{ route('admin.study-program-accreditations.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            + Tambah Akreditasi
        </a>
    </div>

    <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        Nama dokumen pada data awal berasal dari CSV; file sertifikat tidak disertakan dan perlu diunggah satu per satu.
    </div>

    <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari program studi, jenjang, atau nomor SK..."
               class="w-full max-w-lg rounded-lg border px-4 py-2 text-sm">
        <button type="submit" class="rounded-lg border bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Cari</button>
    </form>

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-3">Program Studi</th>
                    <th class="px-5 py-3">Nomor SK</th>
                    <th class="px-5 py-3">Peringkat</th>
                    <th class="px-5 py-3">Berlaku sampai</th>
                    <th class="px-5 py-3">Sertifikat</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($accreditations as $accreditation)
                    <tr>
                        <td class="px-5 py-4 align-top">
                            <p class="font-semibold">{{ $accreditation->name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $accreditation->study_level }}</p>
                        </td>
                        <td class="px-5 py-4 align-top">{{ $accreditation->decree_number }}</td>
                        <td class="px-5 py-4 align-top">{{ $accreditation->rating }}</td>
                        <td class="px-5 py-4 align-top">
                            <span class="{{ $accreditation->valid_until->toDateString() >= today()->toDateString() ? 'text-emerald-700' : 'text-slate-500' }}">
                                {{ $accreditation->valid_until->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 align-top">
                            @if ($accreditation->certificate_path)
                                <a href="{{ route('admin.study-program-accreditations.download', $accreditation) }}" class="font-semibold text-indigo-600 hover:underline">
                                    Unduh sertifikat
                                </a>
                            @else
                                <span class="text-xs font-medium text-amber-700">Belum diunggah</span>
                                @if ($accreditation->source_document_name)
                                    <p class="mt-1 max-w-xs break-all text-xs text-slate-400">{{ $accreditation->source_document_name }}</p>
                                @endif
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right align-top">
                            <div class="inline-flex items-center gap-3">
                                <a href="{{ route('admin.study-program-accreditations.edit', $accreditation) }}" class="font-semibold text-indigo-600 hover:underline">Edit / unggah</a>
                                <form action="{{ route('admin.study-program-accreditations.destroy', $accreditation) }}" method="POST" onsubmit="return confirm('Hapus data akreditasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data akreditasi program studi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $accreditations->links() }}</div>
@endsection
