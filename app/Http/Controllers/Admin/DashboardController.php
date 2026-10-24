<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStores = DB::table('stores')->count();
        $totalUsers = DB::table('users')->count();
        $totalPackages = DB::table('packages')->count();
        $activeSubscriptions = DB::table('subscriptions')->where('status', 'active')->count();

        return view('admin.dashboard', compact('totalStores', 'totalUsers', 'totalPackages', 'activeSubscriptions'));
    }
}
