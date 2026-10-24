<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $store = Store::find(Auth::user()->store_id);
        $hasPremiumStruk = Auth::user()->role === 'super_admin' || (Auth::user()->subscription && Auth::user()->subscription->package && in_array('kustom_struk', Auth::user()->subscription->package->features));
        
        return view('profiles.index', compact('store', 'hasPremiumStruk'));
    }

    public function update(Request $request)
    {
        $hasPremiumStruk = Auth::user()->role === 'super_admin' || (Auth::user()->subscription && Auth::user()->subscription->package && in_array('kustom_struk', Auth::user()->subscription->package->features));
        
        $request->validate([
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'receipt_footer' => 'nullable|string',
        ]);

        $store = Store::find(Auth::user()->store_id);
        
        $data = ['name' => $request->name];
        
        if ($hasPremiumStruk) {
            $data['receipt_footer'] = $request->receipt_footer;
            
            if ($request->hasFile('logo')) {
                if ($store->logo_path) {
                    Storage::disk('public')->delete($store->logo_path);
                }
                $data['logo_path'] = $request->file('logo')->store('logos', 'public');
            }
        }

        $store->update($data);

        return redirect()->route('profiles.index')->with('success', 'Profil Toko berhasil diperbarui.');
    }
}
