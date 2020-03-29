<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin {
    public function handle($request, Closure $next)
    {
        if(!Auth::user()['admin']) {
          return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}