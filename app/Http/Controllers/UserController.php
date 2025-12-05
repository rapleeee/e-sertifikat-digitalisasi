<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(10);

        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'guru' => 'Guru',
            'staf' => 'Staf',
            'yayasan' => 'Yayasan',
            'perusahaan' => 'Perusahaan',
        ];

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:super_admin,admin,guru,staf,yayasan,perusahaan'],
        ]);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:super_admin,admin,guru,staf,yayasan,perusahaan'],
        ]);

        $user->update([
            'role' => $request->input('role'),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
