<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use App\Models\Package;
use App\Models\Store;
use App\Models\User;

class TenantController extends Controller
{
    public function index()
    {
        $stores = DB::table('stores')
            ->leftJoin('users', 'stores.id', '=', 'users.store_id')
            ->select('stores.*', 'users.name as owner_name', 'users.email as owner_email')
            ->where('users.role', 'tenant')
            ->get();
            
        return view('admin.tenants.index', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Store baru
            $store = Store::create([
                'name' => $request->store_name,
            ]);

            // 2. Buat User baru yang terkait dengan Store
            User::create([
                'name' => $request->owner_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tenant',
                'store_id' => $store->id,
            ]);
        });

        return back()->with('success', 'Pelanggan / Toko baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        $store = DB::table('stores')->where('id', $id)->first();
        $packages = Package::where('is_active', true)->get();
        $subscriptions = Subscription::with('package')->where('store_id', $id)->latest()->get();
        
        return view('admin.tenants.show', compact('store', 'packages', 'subscriptions'));
    }

    public function subscribe(Request $request, $id)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::findOrFail($request->package_id);
        
        // Matikan subskripsi lama jika ada yang aktif
        Subscription::where('store_id', $id)->where('status', 'active')->update(['status' => 'expired']);

        // Buat subskripsi baru
        Subscription::create([
            'store_id' => $id,
            'package_id' => $package->id,
            'start_date' => now(),
            'end_date' => now()->addDays($package->duration_days),
            'status' => 'active'
        ]);

        return back()->with('success', 'Paket berlangganan berhasil diaktifkan untuk toko ini!');
    }

    public function unsubscribe(Request $request, $id)
    {
        // Cari subskripsi aktif
        $subscription = Subscription::where('store_id', $id)
            ->where('status', 'active')
            ->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'expired',
                'end_date' => now()
            ]);
            return back()->with('success', 'Paket langganan berhasil dinonaktifkan.');
        }

        return back()->with('error', 'Tidak ada paket langganan aktif untuk dinonaktifkan.');
    }
}
