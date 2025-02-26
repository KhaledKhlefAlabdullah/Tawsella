<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Interfaces\IMustVerifyEmailByCode;

class EnsureEmailIsVerifiedByCodeMiddleware
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        if ($request->user() && $request->user() instanceof IMustVerifyEmailByCode && !$request->user()->hasVerifiedEmailByCode()) {
            return api_response(message: 'بريدك غير مأكد قم بتأكيده قبل ');
        }
        return $next($request);
    }
}
