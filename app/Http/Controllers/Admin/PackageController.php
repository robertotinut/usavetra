<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        Package::create($request->all());
        return back()->with('success', 'Paket berhasil ditambahkan!');
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        $package->update($request->all());
        return back()->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return back()->with('success', 'Paket berhasil dihapus!');
    }
}
