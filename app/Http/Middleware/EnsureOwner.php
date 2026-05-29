<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect('/login');
        }

        if ($request->user()->role === 'superadmin') {
            return redirect('/admin/dashboard');
        }

        if ($request->user()->role !== 'owner') {
            abort(403, 'Akun ini tidak memiliki akses owner.');
        }

        if ($request->user()->status === 'suspended') {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors([
                'email' => 'Akun kamu sedang dinonaktifkan. Hubungi admin DagangFlow.',
            ]);
        }

        return $next($request);
    }
}