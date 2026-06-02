<?php
// app/Http/Middleware/SetLocale.php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED_LOCALES = ['uz', 'ru', 'en'];
    private const DEFAULT_LOCALE = 'uz';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? self::DEFAULT_LOCALE;

        if (!in_array($locale, self::SUPPORTED_LOCALES, strict: true)) {
            $locale = self::DEFAULT_LOCALE;
        }

        app()->setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
