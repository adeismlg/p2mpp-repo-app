@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
    @endphp

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <p class="text-sm text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h1 class="text-2xl font-bold">{{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }} 👋</h1>
        </div>
        <a href="{{ route('admin.documents.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-600/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Unggah Dokumen
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'Total Dokumen', 'value' => $stats['documents'], 'from' => 'from-indigo-500', 'to' => 'to-indigo-600', 'icon' => 'doc'],
                ['label' => 'Kategori', 'value' => $stats['categories'], 'from' => 'from-violet-500', 'to' => 'to-violet-600', 'icon' => 'folder'],
                ['label' => 'Berita', 'value' => $stats['news'], 'from' => 'from-pink-500', 'to' => 'to-pink-600', 'icon' => 'news'],
                ['label' => 'Pelatihan', 'value' => $stats['courses'], 'from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'icon' => 'book'],
                ['label' => 'Total Unduhan', 'value' => $stats['downloads'], 'from' => 'from-amber-500', 'to' => 'to-amber-600', 'icon' => 'download'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="relative overflow-hidden bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} rounded-2xl p-5 text-white shadow-lg">
                <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10"></div>
                <div class="absolute -right-8 -bottom-8 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="h-9 w-9 rounded-lg bg-white/20 flex items-center justify-center mb-4">
                        @switch($card['icon'])
                            @case('doc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                @break
                            @case('folder')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" /></svg>
                                @break
                            @case('news')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                @break
                            @case('book')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                @break
                            @default
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                        @endswitch
                    </div>
                    <p class="text-xs font-medium text-white/80 mb-1">{{ $card['label'] }}</p>
                    <p class="text-2xl font-extrabold">{{ number_format($card['value']) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        {{-- Chart: unduhan per kategori --}}
        <div class="lg:col-span-2 bg-white border rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold">Unduhan per Kategori</h2>
                    <p class="text-xs text-slate-400">6 kategori dengan unduhan terbanyak</p>
                </div>
            </div>
            @if ($categoryStats->isEmpty())
                <p class="text-sm text-slate-400 py-10 text-center">Belum ada data untuk ditampilkan.</p>
            @else
                <canvas id="categoryChart" height="110"></canvas>
            @endif
        </div>

        {{-- Donut: publik vs tersembunyi --}}
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="font-bold mb-1">Visibilitas Dokumen</h2>
            <p class="text-xs text-slate-400 mb-4">Publik vs tersembunyi</p>
            @if ($stats['documents'] === 0)
                <p class="text-sm text-slate-400 py-10 text-center">Belum ada dokumen.</p>
            @else
                <canvas id="visibilityChart" height="180"></canvas>
                <div class="flex justify-center gap-6 mt-4 text-xs">
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span> Publik ({{ $publicCount }})</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span> Tersembunyi ({{ $hiddenCount }})</div>
                </div>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Dokumen terpopuler --}}
        <div class="lg:col-span-2 bg-white border rounded-2xl overflow-hidden">
            <div class="p-6 pb-0">
                <h2 class="font-bold">Dokumen Terpopuler</h2>
                <p class="text-xs text-slate-400 mb-4">Berdasarkan jumlah unduhan</p>
            </div>
            @if ($topDocuments->isEmpty())
                <p class="text-sm text-slate-400 text-center py-10">Belum ada dokumen.</p>
            @else
                <table class="w-full text-sm">
                    <tbody class="divide-y">
                        @foreach ($topDocuments as $i => $document)
                            <tr>
                                <td class="pl-6 py-3 w-8 text-slate-400 font-semibold">{{ $i + 1 }}</td>
                                <td class="py-3">
                                    <p class="font-medium">{{ $document->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $document->category?->name ?? 'Umum' }}</p>
                                </td>
                                <td class="pr-6 py-3 text-right">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full">
                                        {{ number_format($document->downloads_count) }}x
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="font-bold mb-1">Dokumen Terbaru</h2>
            <p class="text-xs text-slate-400 mb-4">6 unggahan terakhir</p>
            @if ($recentDocuments->isEmpty())
                <p class="text-sm text-slate-400 text-center py-10">Belum ada aktivitas.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($recentDocuments as $document)
                        <li class="flex items-start gap-3">
                            <div class="h-8 w-8 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($document->file_type ?? '?', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate">{{ $document->title }}</p>
                                <p class="text-xs text-slate-400">{{ $document->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    @if ($categoryStats->isNotEmpty() || $stats['documents'] > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            @if ($categoryStats->isNotEmpty())
                new Chart(document.getElementById('categoryChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($categoryStats->pluck('name')) !!},
                        datasets: [{
                            label: 'Unduhan',
                            data: {!! json_encode($categoryStats->pluck('downloads')) !!},
                            backgroundColor: '#6366f1',
                            borderRadius: 8,
                            maxBarThickness: 40,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            @endif

            @if ($stats['documents'] > 0)
                new Chart(document.getElementById('visibilityChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Publik', 'Tersembunyi'],
                        datasets: [{
                            data: [{{ $publicCount }}, {{ $hiddenCount }}],
                            backgroundColor: ['#6366f1', '#cbd5e1'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });
            @endif
        </script>
    @endif
@endsection
