<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Student
{
    /**
     * Middleware untuk memastikan hanya mahasiswa (bukan admin) yang bisa akses route tertentu.
     * Route ini mencegah admin mengakses route mahasiswa seperti /qrcode/my-code dan /qrcode/generate.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini. Halaman ini hanya untuk mahasiswa.');
    }
}
