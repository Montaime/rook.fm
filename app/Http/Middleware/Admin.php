<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !array_key_exists($request->user()->id, [1, 2, 3])) {
            abort(403, 'You are not authorized to view this page');
        }

        return $next($request);
    }
}
