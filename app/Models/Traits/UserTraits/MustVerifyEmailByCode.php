<?php

namespace App\Models\Traits\UserTraits;

trait MustVerifyEmailByCode
{
    public function hasVerifiedEmailByCode(): bool
    {
        return !is_null($this->mail_code_verified_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'mail_verify_code' => NULL,
            'mail_code_verified_at' => $this->freshTimestamp(),
            'mail_code_attempts_left' => 0,
        ])->save();
    }

    public function sendEmailVerificationNotification(bool $newData = false): void
    {
        if ($newData) {
            $this->forceFill([
                'mail_verify_code' => random_int(111111, 999999),
                'mail_code_attempts_left' => config('mailCode.max_attempts'),
                'mail_verify_code_sent_at' => now(),
            ])->save();
        }

        // sending the email
        send_mail($this->mail_verify_code, $this->email);
    }
}
