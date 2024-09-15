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
            'code.required' => 'رمز التأكيد مطلوب',
            'code.numeric' => 'يجب أن يكون رمز التأكيد عبارة عن أرقام',
            'code.digits' => 'بجي أن يتألف رمز التأكيد من 6 خانات'
        ]);

        if ($request->code === $user->mail_verify_code) {
            // Convert string to Carbon instance for mail_verify_code_sent_at
            $mailVerifyCodeSentAt = Carbon::parse($user->mail_verify_code_sent_at);

            // Check if code is still valid
            $secondsOfValidation = (int) config('mailCode.seconds_of_validation');

            if ($secondsOfValidation > 0 && $mailVerifyCodeSentAt->diffInSeconds() > $secondsOfValidation) {

                $user->sendEmailVerificationNotification(true);

                return api_response(errors: ' لقد انتهت صلاحية رمز التأكيد حاول مرة أخرى ', code: 500);
            } else {

                $user->markEmailAsVerified();
                return api_response(message: 'تم تأكيد حسابك بنجاح');
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
                    return api_response(errors: 'هناك خطأ في التحقق من الحساب إنتظر ' . $seconds_left . ' ثواني قبل المحاولة مرة أخرى', code: 500);
                }

                // Send new code and set new attempts when the user is no longer banned
                $user->sendEmailVerificationNotification(true);
                return api_response(message: 'تم إرسال رمز جديد إلى بريدك', code: 500);
            }

            $user->decrement('mail_code_attempts_left');
            $user->update(['mail_code_last_attempt_date' => now()]);
            return api_response(errors: 'لقد قمت ب' . $user->mail_code_attempts_left . ' محاولات', code: 500);
        }

        return api_response(errors: 'الرمز الذي أدخلته خاطء حاول مرة أخرى', code: 500);
    }
}
