<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOutletAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $outletId = $request->header('x-outlet-id');

        $outlet = $request->user()->outlets()->find($outletId);

        if (!$outlet) {
            return response()->json(['message' => 'Outlet access revoked'], 403);
        }

        return $next($request);
    }
}
