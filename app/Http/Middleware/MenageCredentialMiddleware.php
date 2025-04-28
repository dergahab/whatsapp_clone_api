<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenageCredentialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        $type = auth()->user()->type;
        if ($type == 'user') {
            return rp_response( ['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
