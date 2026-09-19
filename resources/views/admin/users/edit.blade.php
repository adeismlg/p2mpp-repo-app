@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Pengguna</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white border rounded-xl p-6 max-w-lg space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password Baru (kosongkan jika tidak diganti)</label>
            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                <option value="editor" @selected(old('role', $user->role) === 'editor')>Editor / Staff — kelola konten saja</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin — akses penuh termasuk kelola pengguna</option>
            </select>
            @if ($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role }}">
                <p class="text-xs text-slate-400 mt-1">Role akun sendiri tidak bisa diubah dari sini.</p>
            @endif
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
@endsection
