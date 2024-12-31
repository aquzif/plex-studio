<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user() && auth()->user()->isAdmin()){
            return $next($request);
        }
        session()->flash('error', 'You are not authorized to access this page');
        return redirect()->back();
    }
}
