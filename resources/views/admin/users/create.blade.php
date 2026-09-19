@extends('layouts.admin')

@section('title', 'Akun Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Akun Pengguna Baru</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white border rounded-xl p-6 max-w-lg space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="editor" selected>Editor / Staff — kelola konten saja</option>
                <option value="admin">Admin — akses penuh termasuk kelola pengguna</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
