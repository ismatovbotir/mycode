<?php
// app/Http/Middleware/EnsureMoySkladToken.php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMoySkladToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only for admin users
        if ($user && $user->role === 'admin' && !$user->moysklad_token) {
            // Skip middleware for setup routes
            if (!$request->routeIs('moysklad-setup.*', 'logout')) {
                return redirect()->route('moysklad-setup.index');
            }
        }

        return $next($request);
    }
}
