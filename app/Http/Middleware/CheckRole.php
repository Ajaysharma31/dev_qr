<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the authenticated user has the specified role.
        if ($request->user() && $request->user()->role === $role) {
            return $next($request);
        }

        // Redirect or return an error response if the role check fails.
        return abort(403, 'Unauthorized.');
    }
}
