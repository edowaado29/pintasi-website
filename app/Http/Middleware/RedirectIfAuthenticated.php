<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) { // Periksa apakah pengguna sudah login
            $user = Auth::user(); // Dapatkan pengguna yang sedang login

            // Redirect ke dashboard berdasarkan role
            if ($user->role === 'kader') {
                return redirect()->route('dashboard_kader');
            } elseif ($user->role === 'bidan') {
                return redirect()->route('dashboard');
            }
        }

        return $next($request); // Lanjutkan ke request berikutnya jika belum login
    }
}