<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $trustedIps = [
            '198.27.83.222',
            '192.99.21.124',
            '167.114.64.88',
            '167.114.64.21',
            '192.168.0.102',
        ];

        if (!in_array($request->ip(), $trustedIps)) {
            abort(403);
        }

        return $next($request);
    }
}