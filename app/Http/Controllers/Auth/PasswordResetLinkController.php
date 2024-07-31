<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CustomResetPassword;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('passwords.user')],
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            $token = app('auth.password.broker')->createToken($user);
            Mail::to($request->email)->send(new CustomResetPassword($token, $request->email));
            return response()->json(['status' => __($status)]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
