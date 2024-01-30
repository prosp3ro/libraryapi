<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is not an admin
        if ($request->user() && $request->user()->is_admin) {
            return response()->json(['error' => 'Administrators are not allowed to perform this query'], 403);
        }

        return $next($request);
    }
}
