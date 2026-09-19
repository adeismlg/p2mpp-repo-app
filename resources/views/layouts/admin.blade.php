<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-100 text-slate-800">
<div class="flex min-h-screen">
    <aside class="w-60 bg-slate-900 text-slate-300 p-5 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="block text-white font-bold text-lg mb-1">Admin Panel</a>
        @auth
            <p class="text-xs text-slate-500 mb-7">Masuk sebagai {{ auth()->user()->roleLabel() }}</p>
        @endauth
        <nav class="space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : '' }}">Dashboard</a>
            <a href="{{ route('admin.documents.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.documents.*') ? 'bg-slate-800 text-white' : '' }}">Repository Dokumen</a>
            <a href="{{ route('admin.news.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.news.*') ? 'bg-slate-800 text-white' : '' }}">Berita</a>
            <a href="{{ route('admin.courses.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.courses.*') ? 'bg-slate-800 text-white' : '' }}">Pelatihan</a>
            <a href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.pages.*') ? 'bg-slate-800 text-white' : '' }}">Halaman Konten</a>

            @if (auth()->user()?->isAdmin())
                <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wide text-slate-500">Khusus Admin</p>
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-white' : '' }}">Kategori Dokumen</a>
                <a href="{{ route('admin.menu.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.menu.*') ? 'bg-slate-800 text-white' : '' }}">Menu Navigasi</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white' : '' }}">Manajemen Staff</a>
            @endif
        </nav>
        <div class="mt-10 pt-4 border-t border-slate-800">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-xs text-slate-400 hover:text-white">&larr; Lihat situs publik</a>
            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block w-full text-left px-3 py-2 text-xs text-slate-400 hover:text-white">Keluar</button>
                </form>
            @endif
        </div>
    </aside>

    <div class="flex-1 p-8">
        @if (session('status'))
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-3 rounded-lg text-sm mb-6">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg text-sm mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>
