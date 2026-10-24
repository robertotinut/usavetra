<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'kasir') {
            abort(403, 'Akses Ditolak! Halaman ini hanya untuk Pemilik Toko (Owner).');
        }
        
        return $next($request);
    }
}
