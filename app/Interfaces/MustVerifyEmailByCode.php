<?php

namespace App\Interfaces;

interface MustVerifyEmailByCode
{
    public function hasVerifiedEmailByCode();

    public function markEmailAsVerified();

    public function sendEmailVerificationNotification();
}
