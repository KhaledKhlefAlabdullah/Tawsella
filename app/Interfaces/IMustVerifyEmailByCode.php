<?php

namespace App\Interfaces;

interface IMustVerifyEmailByCode
{
    public function hasVerifiedEmailByCode();

    public function markEmailAsVerified();

    public function sendEmailVerificationNotification();
}
