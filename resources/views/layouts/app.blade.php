<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UPA Bahasa') — Nama Unit Anda</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi Nama Unit Anda — informasi, program, berita, dan repository dokumen.')">
    <meta property="og:title" content="@yield('title', 'Nama Unit Anda')">
    <meta property="og:description" content="@yield('meta_description', 'Website resmi Nama Unit Anda — informasi, program, berita, dan repository dokumen.')">
    <meta property="og:type" content="website">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📄</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">

    <header class="bg-white border-b sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="font-bold text-lg text-indigo-700">Nama Unit Anda</a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-indigo-700">Beranda</a>
                <a href="{{ route('news.index') }}" class="hover:text-indigo-700">Berita</a>
                <a href="{{ route('courses.index') }}" class="hover:text-indigo-700">Program</a>
                <a href="{{ route('documents.index') }}" class="hover:text-indigo-700">Repository Dokumen</a>
            </nav>

            <a href="{{ route('documents.index') }}" class="hidden md:inline-flex bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700">
                Cari Dokumen
            </a>

            <button id="mobile-menu-btn" class="md:hidden p-2 -mr-2" aria-label="Buka menu" aria-expanded="false">
                <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav id="mobile-menu" class="hidden md:hidden border-t bg-white">
            <div class="px-4 py-3 flex flex-col gap-1 text-sm font-medium">
                <a href="{{ route('home') }}" class="py-2 hover:text-indigo-700">Beranda</a>
                <a href="{{ route('news.index') }}" class="py-2 hover:text-indigo-700">Berita</a>
                <a href="{{ route('courses.index') }}" class="py-2 hover:text-indigo-700">Program</a>
                <a href="{{ route('documents.index') }}" class="py-2 hover:text-indigo-700">Repository Dokumen</a>
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
                <p class="font-bold text-white mb-2">Nama Unit Anda</p>
                <p>Deskripsi singkat unit/lembaga kamu di sini.</p>
            </div>
            <div>
                <p class="font-semibold text-white mb-2">Tautan</p>
                <ul class="space-y-1">
                    <li><a href="{{ route('documents.index') }}" class="hover:text-white">Repository Dokumen</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-white">Berita</a></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-white">Program</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white mb-2">Kontak</p>
                <p>email@contoh.ac.id</p>
            </div>
        </div>
        <div class="text-center text-xs text-slate-500 py-4 border-t border-slate-800">
            &copy; {{ date('Y') }} Nama Unit Anda. Semua hak dilindungi.
        </div>
    </footer>
</body>
</html>
