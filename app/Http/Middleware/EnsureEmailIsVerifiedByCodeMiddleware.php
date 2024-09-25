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
            return api_response(message: 'Your email is not verified. Please verify it before attempting to access.');
        }
        return $next($request);
    }
}
