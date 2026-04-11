<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $locale = substr($request->header('Accept-Language'), 0, 2);

    if (in_array($locale, ['ar', 'en'])) {
        app()->setLocale($locale);
    }

    return $next($request);
}
}

