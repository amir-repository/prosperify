<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {;

        $is_not_admin = auth()->user()->type !== 'admin';
        if ($is_not_admin) {
            abort(403, 'You are not Admin');
        }
        return $next($request);
    }
}
