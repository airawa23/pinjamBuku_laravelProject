<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnerRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user authenticated dan memiliki role 'owner'
        if (!auth()->check() || auth()->user()->role !== 'owner') {
            return redirect()->route('home')->with('error', 'Hanya pemilik buku yang bisa melakukan aksi ini.');
        }

        return $next($request);
    }
}
