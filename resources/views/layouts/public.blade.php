<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'P2MPP') — Pusat Penjaminan Mutu dan Pengembangan Pembelajaran</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi P2MPP Politeknik Negeri Malang — SPMI, akreditasi, pelatihan, berita, dan repository dokumen.')">
    <meta property="og:title" content="@yield('title', 'P2MPP Polinema')">
    <meta property="og:description" content="@yield('meta_description', 'Website resmi P2MPP Politeknik Negeri Malang — SPMI, akreditasi, pelatihan, berita, dan repository dokumen.')">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-polinema.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">

    @php
        // Menu diambil langsung dari database supaya admin bisa atur sendiri
        // lewat panel /admin/menu — tidak perlu ubah kode sama sekali.
        $mainMenu = \App\Models\MenuItem::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    @endphp

    <header class="bg-white border-b sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-bold text-lg text-indigo-700 leading-tight">
                <img src="{{ asset('images/logo-polinema.png') }}" alt="Logo Politeknik Negeri Malang" class="h-11 w-11 object-contain">
                <span>P2MPP <span class="block text-[10px] font-medium text-slate-400 uppercase tracking-wide">Politeknik Negeri Malang</span></span>
            </a>

            <nav class="hidden lg:flex items-center gap-1 text-sm font-medium">
                @foreach ($mainMenu as $item)
                    @if ($item->children->isEmpty())
                        <a href="{{ $item->resolvedUrl() }}"
                           @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                           class="px-3 py-2 rounded-lg hover:bg-slate-100 hover:text-indigo-700">
                            {{ $item->localizedLabel() }}
                        </a>
                    @else
                        <div class="relative group">
                            <button type="button" class="px-3 py-2 rounded-lg hover:bg-slate-100 hover:text-indigo-700 inline-flex items-center gap-1">
                                {{ $item->localizedLabel() }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute left-0 top-full pt-1 hidden group-hover:block min-w-[220px] z-50">
                                <div class="bg-white border rounded-xl shadow-lg py-2">
                                    @foreach ($item->children as $child)
                                        <a href="{{ $child->resolvedUrl() }}"
                                           @if ($child->open_in_new_tab) target="_blank" rel="noopener" @endif
                                           class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-indigo-700">
                                            {{ $child->localizedLabel() }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                <div class="flex items-center gap-1 text-xs font-semibold">
                    <a href="{{ route('locale.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'text-indigo-700' : 'text-slate-400 hover:text-indigo-700' }}">ID</a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-indigo-700' : 'text-slate-400 hover:text-indigo-700' }}">EN</a>
                </div>
                <a href="{{ route('documents.index') }}" class="inline-flex min-w-[11rem] items-center justify-center text-center bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
                    {{ __('public.document_repository') }}
                </a>
            </div>

            <button id="mobile-menu-btn" class="lg:hidden p-2 -mr-2" aria-label="{{ __('public.open_menu') }}" aria-expanded="false">
                <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav id="mobile-menu" class="hidden lg:hidden border-t bg-white">
            <div class="px-4 py-3 flex flex-col gap-1 text-sm font-medium">
                <div class="flex items-center gap-2 py-2 text-xs font-semibold">
                    <span class="text-slate-400">{{ __('public.language_indonesian') }}:</span>
                    <a href="{{ route('locale.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'text-indigo-700' : 'text-slate-400 hover:text-indigo-700' }}">ID</a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-indigo-700' : 'text-slate-400 hover:text-indigo-700' }}">EN</a>
                </div>
                @foreach ($mainMenu as $item)
                    @if ($item->children->isEmpty())
                        <a href="{{ $item->resolvedUrl() }}" class="py-2 hover:text-indigo-700">{{ $item->localizedLabel() }}</a>
                    @else
                        <details class="py-1">
                            <summary class="py-1 cursor-pointer list-none flex items-center justify-between">
                                {{ $item->localizedLabel() }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="pl-4 flex flex-col gap-1 mt-1">
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->resolvedUrl() }}" class="py-1.5 text-slate-600 hover:text-indigo-700">{{ $child->localizedLabel() }}</a>
                                @endforeach
                            </div>
                        </details>
                    @endif
                @endforeach
            </div>
        </nav>
    </header>

    <script>
        (function () {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            var iconOpen = document.getElementById('icon-open');
            var iconClose = document.getElementById('icon-close');

            btn.addEventListener('click', function () {
                var isHidden = menu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden', !isHidden);
                iconClose.classList.toggle('hidden', isHidden);
                btn.setAttribute('aria-expanded', String(!isHidden));
            });
        })();
    </script>

    @if (session('status'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8 text-sm">
            <div>
                <p class="font-bold text-white mb-2">P2MPP Polinema</p>
                <p>{{ __('public.site_description') }}</p>
            </div>
            <div>
                <p class="font-semibold text-white mb-2">{{ __('public.links') }}</p>
                <ul class="space-y-1">
                    <li><a href="{{ route('documents.index') }}" class="hover:text-white">{{ __('public.document_repository') }}</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-white">{{ __('public.news') }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-white">{{ __('public.training') }}</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white mb-2">{{ __('public.contact') }}</p>
                <p>p2mpp@polinema.ac.id</p>
                <p>0341-404424/40</p>
            </div>
        </div>
        <div class="text-center text-xs text-slate-500 py-4 border-t border-slate-800">
            &copy; {{ date('Y') }} P2MPP — Politeknik Negeri Malang. {{ __('public.all_rights_reserved') }}
        </div>
    </footer>
</body>
</html>
