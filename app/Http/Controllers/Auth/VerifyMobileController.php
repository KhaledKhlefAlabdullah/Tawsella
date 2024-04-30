<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Exceptions\TwilioException;
use App\Providers\RouteServiceProvider;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Twilio\Exceptions\ConfigurationException;

class VerifyMobileController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($request->code === $user->mobile_verify_code) {
            // Convert string to Carbon instance for mobile_verify_code_sent_at
            $mobileVerifyCodeSentAt = Carbon::parse($user->mobile_verify_code_sent_at);

            // Check if code is still valid
            $secondsOfValidation = (int) config('mobile.seconds_of_validation');
            if ($secondsOfValidation > 0 && $mobileVerifyCodeSentAt->diffInSeconds() > $secondsOfValidation) {
                $user->sendMobileVerificationNotification(true);
                return api_response(errors: ' لقد انتهت صلاحية رمز التأكيد حاول مرة أخرى ', code:500);
            } else {
                $user->markMobileAsVerified();
                return api_response(message:'تم تأكيد حسابك بنجاح');
            }
        }

        // Convert string to Carbon instance for mobile_last_attempt_date
        $mobileLastAttemptDate = Carbon::parse($user->mobile_last_attempt_date);

        // Max attempts feature
        if (config('mobile.max_attempts') > 0) {
            if ($user->mobile_attempts_left <= 1) {
                if ($user->mobile_attempts_left == 1) {
                    $user->decrement('mobile_attempts_left');
                }

                // Check how many seconds left to get unbanned after no more attempts left
                $seconds_left = (int) config('mobile.attempts_ban_seconds') - $mobileLastAttemptDate->diffInSeconds();
                if ($seconds_left > 0) {
                    return api_response(errors: 'هناك خطأ في التحقق من الحساب إنتظر ' . $seconds_left . ' ثواني قبل المحاولة مرة أخرى', code:500);
                }

                // Send new code and set new attempts when the user is no longer banned
                $user->sendMobileVerificationNotification(true);
                return api_response(message:'تم إرسال رمز جديد إلى بريدك', code:500);
            }

            $user->decrement('mobile_attempts_left');
            $user->update(['mobile_last_attempt_date' => now()]);
            return api_response(errors:'لقد قمت ب'.$user->mobile_attempts_left.' محاولات', code:500);
        }

        return api_response(errors: 'الرمز الذي أدخلته خاطء حاول مرة أخرى', code:500);
    }
}
