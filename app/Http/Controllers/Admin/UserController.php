<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,editor,guest',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', "Pengguna {$data['name']} berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'required|in:admin,editor,guest',
        ]);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['role' => 'Kamu tidak bisa mengubah role akun sendiri.']);
        }

        $user->update($data);

        return back()->with('status', "Role {$user->name} berhasil diubah menjadi {$user->roleLabel()}.");
    }
}
