<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, explode(',', $roles))) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
