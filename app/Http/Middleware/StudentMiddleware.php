<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isStudent()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'You do not have permission to access this area.');
    }
}
