<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Cek apakah pengguna terautentikasi dan memiliki role yang sesuai
        if (!Auth::check() || Auth::user()->role->nama_role !== $role) {
            // Jika tidak sesuai, kembalikan respon atau redirect
            return redirect()->route('home')->with('error', 'You do not have access to this section.');
        }

        return $next($request);
    }
}
