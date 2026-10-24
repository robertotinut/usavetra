<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckPremium
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $feature = null): Response
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'super_admin') {
                return $next($request);
            }

            $subscription = Auth::user()->subscription;
            $now = \Carbon\Carbon::now();
            
            // Cek apakah langganan ada, paket ada, dan masa aktif belum habis
            if ($subscription && $subscription->package && \Carbon\Carbon::parse($subscription->end_date)->greaterThanOrEqualTo($now)) {
                // Jika butuh fitur spesifik, cek apakah paket mendukung
                if ($feature && !in_array($feature, $subscription->package->features)) {
                    return redirect()->route('upgrade')->with('upgrade_message', 'Paket langganan Anda tidak mendukung fitur ini.');
                }
                return $next($request);
            }
        }
        
        return redirect()->route('upgrade')->with('upgrade_message', 'Masa aktif Premium Anda telah habis atau Anda belum berlangganan. Silakan Upgrade untuk mengakses fitur ini.');
    }
}
