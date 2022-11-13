<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->userType == 1) {
                return $next($request);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'this is only for the main admin, go away from here!',
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'you are not logged in!',
            ], 200);
        }
    }
}
