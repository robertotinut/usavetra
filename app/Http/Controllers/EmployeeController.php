<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $hasPremium = Auth::user()->role === 'super_admin' || (Auth::user()->subscription && Auth::user()->subscription->package && in_array('manajemen_karyawan', Auth::user()->subscription->package->features));
        
        $employees = User::where('store_id', Auth::user()->store_id)->where('id', '!=', Auth::id())->get();
        return view('employees.index', compact('employees', 'hasPremium'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hasPremium = Auth::user()->role === 'super_admin' || (Auth::user()->subscription && Auth::user()->subscription->package && in_array('manajemen_karyawan', Auth::user()->subscription->package->features));
        if (!$hasPremium) {
            return back()->with('premium_error', 'Fitur Manajemen Karyawan khusus untuk pengguna Premium.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
            'store_id' => Auth::user()->store_id,
        ]);

        return back()->with('success', 'Karyawan / Kasir berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::where('store_id', Auth::user()->store_id)->findOrFail($id);
        if ($employee->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri!');
        }
        $employee->delete();
        return back()->with('success', 'Karyawan / Kasir berhasil dihapus!');
    }
}
