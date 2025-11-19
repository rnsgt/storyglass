<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login DAN role-nya 'admin', silakan lewat
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        // Jika bukan admin, tendang ke halaman utama atau tampilkan error 403
        abort(403, 'AKSES DITOLAK: Anda bukan King Admin!');
    }
}
