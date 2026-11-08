@extends('layouts.admin')

@section('title', 'Matriks Bukti Akreditasi')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Matriks Bukti Akreditasi</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola kriteria, penanggung jawab, tenggat, dan berkas bukti secara internal.</p>
        </div>
        <a href="{{ route('admin.accreditation-evidences.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            + Tambah Bukti
        </a>
    </div>

    <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari kriteria atau bukti..."
               class="w-full max-w-sm rounded-lg border px-4 py-2 text-sm">
        <select name="status" class="rounded-lg border px-3 py-2 text-sm">
            <option value="">Semua status</option>
            @foreach (\App\Models\AccreditationEvidence::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg border bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Filter</button>
    </form>

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-3">Kriteria</th>
                    <th class="px-5 py-3">Bukti</th>
                    <th class="px-5 py-3">Penanggung jawab</th>
                    <th class="px-5 py-3">Tenggat</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($evidences as $evidence)
                    <tr>
                        <td class="px-5 py-4 align-top">
                            <p class="font-semibold">{{ $evidence->criterion_code }} — {{ $evidence->criterion_name }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ \App\Models\AccreditationEvidence::SCOPES[$evidence->scope] ?? $evidence->scope }}
                                @if ($evidence->assessment_entity)
                                    · {{ $evidence->assessment_entity }}
                                @endif
                                @if ($evidence->instrument)
                                    · {{ $evidence->instrument }}
                                @endif
                            </p>
                        </td>
                        <td class="px-5 py-4 align-top">
                            <p>{{ $evidence->evidence_title }}</p>
                            @if ($evidence->evidence_path)
                                <a href="{{ route('admin.accreditation-evidences.download', $evidence) }}" class="mt-1 inline-block text-xs font-semibold text-indigo-600 hover:underline">Unduh berkas</a>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-top">{{ $evidence->owner?->name ?? 'Belum ditentukan' }}</td>
                        <td class="px-5 py-4 align-top">{{ $evidence->due_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-5 py-4 align-top">
                            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                {{ \App\Models\AccreditationEvidence::STATUSES[$evidence->status] ?? $evidence->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right align-top">
                            <div class="inline-flex items-center gap-3">
                                <a href="{{ route('admin.accreditation-evidences.edit', $evidence) }}" class="font-semibold text-indigo-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.accreditation-evidences.destroy', $evidence) }}" method="POST" onsubmit="return confirm('Hapus data bukti ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada bukti akreditasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $evidences->links() }}</div>
@endsection
