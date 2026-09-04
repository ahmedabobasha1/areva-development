<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->route('locale');
        $supported = config('areva.locales', ['en', 'ar']);

        if (! in_array($locale, $supported, true)) {
            abort(404);
        }

        app()->setLocale($locale);
        $request->attributes->set('locale', $locale);

        return $next($request);
    }
}
