<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $selectedStoreId = $request->query('store_id');
        $search = $request->query('search');
        $activeTab = $request->query('tab', 'sessions');

        $now = time();
        $fifteenMinutesAgo = $now - (15 * 60);
        $today = Carbon::today()->toDateString();

        // 1. KPI Metrik Ringkasan
        $onlineUsersCount = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $fifteenMinutesAgo)
            ->distinct('user_id')
            ->count('user_id');

        $activeStoresToday = Sale::whereDate('created_at', $today)
            ->distinct('store_id')
            ->count('store_id');

        $todaySalesCount = Sale::whereDate('created_at', $today)->count();
        $todaySalesSum = Sale::whereDate('created_at', $today)->sum('total_amount');
        $totalStoresCount = Store::count();
        $totalUsersCount = User::count();

        // 2. Daftar Toko untuk Filter
        $stores = Store::orderBy('name')->get();

        // 3. Tab 1: Sesi Pengguna & Online Monitor
        $sessionsQuery = DB::table('sessions')
            ->whereNotNull('user_id')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->select(
                'sessions.id as session_id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name as user_name',
                'users.email as user_email',
                'users.role as user_role',
                'stores.id as store_id',
                'stores.name as store_name'
            )
            ->orderBy('sessions.last_activity', 'desc');

        if ($selectedStoreId) {
            $sessionsQuery->where('users.store_id', $selectedStoreId);
        }

        if ($search) {
            $sessionsQuery->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('sessions.ip_address', 'like', "%{$search}%")
                  ->orWhere('stores.name', 'like', "%{$search}%");
            });
        }

        $sessions = $sessionsQuery->limit(150)->get()->map(function ($s) use ($now, $fifteenMinutesAgo) {
            $diffSeconds = $now - $s->last_activity;
            if ($diffSeconds <= (15 * 60)) {
                $s->status = 'online';
                $s->status_label = 'Online';
                $s->badge_class = 'bg-success-subtle text-success';
            } elseif ($diffSeconds <= (60 * 60)) {
                $s->status = 'idle';
                $s->status_label = 'Baru Saja';
                $s->badge_class = 'bg-warning-subtle text-warning';
            } else {
                $s->status = 'offline';
                $s->status_label = 'Offline';
                $s->badge_class = 'bg-secondary-subtle text-secondary';
            }

            $s->time_ago = Carbon::createFromTimestamp($s->last_activity)->diffForHumans();
            $s->last_seen_date = Carbon::createFromTimestamp($s->last_activity)->format('d M Y, H:i');
            $s->device_info = $this->parseUserAgent($s->user_agent);

            return $s;
        });

        // 4. Tab 2: Feed Transaksi Penjualan Terkini
        $salesQuery = Sale::with(['items'])
            ->leftJoin('stores', 'sales.store_id', '=', 'stores.id')
            ->select('sales.*', 'stores.name as store_name')
            ->orderBy('sales.created_at', 'desc');

        if ($selectedStoreId) {
            $salesQuery->where('sales.store_id', $selectedStoreId);
        }

        $recentSales = $salesQuery->limit(200)->get();

        // 5. Tab 3: Pergerakan Stok & Inventaris
        $stockQuery = StockMovement::with(['product'])
            ->leftJoin('stores', 'stock_movements.store_id', '=', 'stores.id')
            ->select('stock_movements.*', 'stores.name as store_name')
            ->orderBy('stock_movements.created_at', 'desc');

        if ($selectedStoreId) {
            $stockQuery->where('stock_movements.store_id', $selectedStoreId);
        }

        $recentStockMovements = $stockQuery->limit(200)->get();

        // 6. Tab 4: Audit Log Sistem
        $auditQuery = ActivityLog::with(['user', 'store'])
            ->orderBy('created_at', 'desc');

        if ($selectedStoreId) {
            $auditQuery->where('store_id', $selectedStoreId);
        }

        $auditLogs = $auditQuery->limit(200)->get();

        // 7. Toko Teraktif (Top 5 berdasarkan total transaksi)
        $topStores = Store::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.activities.index', compact(
            'onlineUsersCount',
            'activeStoresToday',
            'todaySalesCount',
            'todaySalesSum',
            'totalStoresCount',
            'totalUsersCount',
            'stores',
            'sessions',
            'recentSales',
            'recentStockMovements',
            'auditLogs',
            'topStores',
            'selectedStoreId',
            'search',
            'activeTab'
        ));
    }

    /**
     * Putuskan sesi pengguna (Force Logout)
     */
    public function destroySession($id)
    {
        try {
            DB::table('sessions')->where('id', $id)->delete();
            return back()->with('success', 'Sesi pengguna berhasil diputuskan. Pengguna akan diminta login ulang.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memutuskan sesi: ' . $e->getMessage());
        }
    }

    /**
     * Helper mendeteksi jenis perangkat & browser
     */
    private function parseUserAgent(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return [
                'device' => 'Unknown',
                'browser' => 'Browser',
                'icon' => 'mdi-devices',
                'is_mobile' => false,
            ];
        }

        $isMobile = preg_match('/(android|iphone|ipad|mobile|ipod)/i', $userAgent);
        
        $device = 'Desktop';
        $icon = 'mdi-laptop';
        if (preg_match('/android/i', $userAgent)) {
            $device = 'Android Mobile';
            $icon = 'mdi-cellphone';
        } elseif (preg_match('/(iphone|ipad|ipod)/i', $userAgent)) {
            $device = 'Apple iOS';
            $icon = 'mdi-cellphone-iphone';
        } elseif (preg_match('/windows/i', $userAgent)) {
            $device = 'Windows PC';
            $icon = 'mdi-monitor';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $device = 'Mac OS';
            $icon = 'mdi-apple';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $device = 'Linux PC';
            $icon = 'mdi-linux';
        }

        $browser = 'Web Browser';
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return [
            'device' => $device,
            'browser' => $browser,
            'icon' => $icon,
            'is_mobile' => (bool) $isMobile,
        ];
    }
}
