<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyEmailByCodeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'code' => 'required|numeric|digits:6'
        ], [
            'code.required' => 'The confirmation code is required',
            'code.numeric' => 'The confirmation code must be numeric',
            'code.digits' => 'The confirmation code must be 6 digits'
        ]);

        if ($request->code === $user->mail_verify_code) {
            // Convert string to Carbon instance for mail_verify_code_sent_at
            $mailVerifyCodeSentAt = Carbon::parse($user->mail_verify_code_sent_at);

            // Check if code is still valid
            $secondsOfValidation = (int) config('mailCode.seconds_of_validation');

            if ($secondsOfValidation > 0 && $mailVerifyCodeSentAt->diffInSeconds() > $secondsOfValidation) {

                $user->sendEmailVerificationNotification(true);

                return api_response(code: 500, errors: 'انتهت صلاحية كود التحقق الخاص بك الرجاء المحاولة مرة أخرى');
            } else {

                $user->markEmailAsVerified();
                return api_response(message: 'تم تأكيد حسابك بنجاح.');
            }
        }

        // Convert string to Carbon instance for mail_code_last_attempt_date
        $mailCodeLastAttemptDate = Carbon::parse($user->mail_code_last_attempt_date);

        // Max attempts feature
        if (config('mailCode.max_attempts') > 0) {

            if ($user->mail_code_attempts_left <= 1) {

                if ($user->mail_code_attempts_left == 1) {

                    $user->decrement('mail_code_attempts_left');
                }

                // Check how many seconds left to get unbanned after no more attempts left
                $seconds_left = (int) config('mailCode.attempts_ban_seconds') - $mailCodeLastAttemptDate->diffInSeconds();

                if ($seconds_left > 0) {
                    return api_response(code: 500, errors: 'There was an error verifying the account. Please wait ' . $seconds_left . ' seconds before trying again.');
                }

                // Send new code and set new attempts when the user is no longer banned
                $user->sendEmailVerificationNotification(true);
                return api_response(message: 'تم إرسال رمز تحقق جديد إلى بريدك', code: 500);
            }

            $user->decrement('mail_code_attempts_left');
            $user->update(['mail_code_last_attempt_date' => now()]);
            return api_response(code: 500, errors: 'You have ' . $user->mail_code_attempts_left . ' attempts left.');
        }

        return api_response(code: 500, errors: 'The code you entered is incorrect. Please try again.');
    }
}
