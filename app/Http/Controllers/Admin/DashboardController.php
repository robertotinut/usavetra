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

        $fifteenMinutesAgo = time() - (15 * 60);
        $onlineUsers = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $fifteenMinutesAgo)
            ->distinct('user_id')
            ->count('user_id');

        $recentSales = DB::table('sales')
            ->join('stores', 'sales.store_id', '=', 'stores.id')
            ->select('sales.receipt_number', 'sales.total_amount', 'sales.payment_method', 'sales.created_at', 'stores.name as store_name')
            ->orderBy('sales.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalStores', 'totalUsers', 'totalPackages', 'activeSubscriptions', 'onlineUsers', 'recentSales'));
    }
}
