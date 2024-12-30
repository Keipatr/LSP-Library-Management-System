<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Mengecek apakah pengguna sudah login dan apakah role sesuai
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        // Jika role tidak sesuai, alihkan pengguna
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
