<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
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
