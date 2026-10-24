<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('store')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $stores = Store::orderBy('name')->get();
        return view('admin.users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,tenant,kasir',
            'store_id' => 'nullable|exists:stores,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'store_id' => $request->store_id
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $stores = Store::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:super_admin,tenant,kasir',
            'store_id' => 'nullable|exists:stores,id'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'store_id' => $request->store_id
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
