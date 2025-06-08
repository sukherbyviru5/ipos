<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IntegrateMiddleware
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
        if ($request->key != \env('CBT_INTEGRATION_KEY')) {
            return \response()->json([
                'message' => "Integrasi Gagal Dikarenakan Kunci Integrasi Salah"
            ], Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
