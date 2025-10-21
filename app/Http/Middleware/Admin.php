<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !in_array($request->user()->id, [1, 3])) {
            abort(403, 'You are not authorized to view this page');
        }

        return $next($request);
    }
}
