<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Volunteer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $is_not_volunteer = auth()->user()->type !== 'volunteer';
        if ($is_not_volunteer) {
            abort(403, 'You are not Volunteer');
        }

        return $next($request);
    }
}
