<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRenterRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user authenticated dan memiliki role 'renter'
        if (!auth()->check() || auth()->user()->role !== 'renter') {
            return redirect()->route('home')->with('error', 'Hanya peminjam yang bisa melakukan aksi ini.');
        }

        return $next($request);
    }
}
