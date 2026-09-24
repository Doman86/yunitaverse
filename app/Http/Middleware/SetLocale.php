<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Only these locales are ever accepted — anything else falls back to id.
     */
    public const ALLOWED = ['id', 'en'];

    public const DEFAULT = 'id';

    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->get('lang');

        // 1. Explicit ?lang= switch (validated against the whitelist).
        if (is_string($requested) && in_array($requested, self::ALLOWED, true)) {
            Session::put('locale', $requested);
        }

        // 2. Session value (also validated — never trust the session blindly).
        $locale = Session::get('locale');

        if (! is_string($locale) || ! in_array($locale, self::ALLOWED, true)) {
            $locale = self::DEFAULT;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
