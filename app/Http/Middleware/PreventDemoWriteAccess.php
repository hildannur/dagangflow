<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoWriteAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $isDemoUser = $user && $user->email === 'sirendeskstudio@gmail.com';

        $isWriteRequest = in_array($request->method(), [
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ], true);

        $allowedDemoRoutes = [
            'logout',
        ];

        if (
            $isDemoUser &&
            $isWriteRequest &&
            ! in_array($request->route()?->getName(), $allowedDemoRoutes, true)
        ) {
            return back()->withErrors([
                'demo' => 'Akun demo hanya untuk melihat fitur. Perubahan data tidak bisa disimpan.',
            ]);
        }

        return $next($request);
    }
}