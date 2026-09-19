@extends('layouts.admin')

@section('title', 'Manajemen Staff')

@section('content')
    <h1 class="text-2xl font-bold mb-1">Manajemen Staff</h1>
    <p class="text-slate-500 text-sm mb-6">
        Atur siapa yang boleh masuk panel admin. <strong>Admin</strong> punya akses penuh
        (termasuk kelola kategori & staff), <strong>Editor/Staff</strong> hanya bisa kelola
        dokumen, berita, dan program. <strong>User Biasa</strong> tidak bisa masuk panel sama sekali.
    </p>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if ($user->id === auth()->id())
                                <span class="text-xs font-semibold text-slate-400">{{ $user->roleLabel() }} (akun kamu)</span>
                            @else
                                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="border rounded-lg px-2 py-1 text-xs">
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        <option value="editor" @selected($user->role === 'editor')>Editor/Staff</option>
                                        <option value="guest" @selected($user->role === 'guest')>User Biasa</option>
                                    </select>
                                    <button type="submit" class="text-indigo-600 text-xs font-semibold hover:underline">Simpan</button>
                                </form>
                            @endif
                        </td>
                        <td class="px-5 py-3"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
