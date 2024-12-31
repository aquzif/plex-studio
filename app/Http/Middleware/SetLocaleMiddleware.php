<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->cookie('locale', 'en');
        app()->setLocale($locale);

        return $next($request);
    }
}
