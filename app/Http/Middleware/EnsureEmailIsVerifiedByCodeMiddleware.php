<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Interfaces\MustVerifyEmailByCode;

class EnsureEmailIsVerifiedByCodeMiddleware
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || ($request->user() instanceof MustVerifyEmailByCode && $request->user()->hasVerifiedEmailByCode())) {
            return api_response(message: 'لم يتم تأكيد بريدك قم بتأكيده أولاً قبل محاولة الوصول');
        }
        return $next($request);
    }
}
