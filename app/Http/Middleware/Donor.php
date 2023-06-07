<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Donor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $is_not_admin = auth()->user()->type !== 'donor';
        if ($is_not_admin) {
            abort(403, 'You are not Donor');
        }
        return $next($request);
    }
}
