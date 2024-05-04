<?php

return [

    /*
    |--------------------------------------------------------------------------
    | mail code max attempts
    |--------------------------------------------------------------------------
    |
    | Max attempts to input mail verification code before re-send a new one.
    | Set 0 for not use this feature.
    |
    */

    'max_attempts' => env('MAIL_CODE_MAX_ATTEMPTS', 3)?:0,

    /*
    |--------------------------------------------------------------------------
    | MAIL_CODE seconds of validation
    |--------------------------------------------------------------------------
    |
    | Seconds of validation of the sent verification code (default 5 minutes).
    | Set 0 for not use this feature.
    |
    */

    'seconds_of_validation' => env('MAIL_CODE_SECONDS_OF_VALIDATION', 300)?:0,

    /*
    |--------------------------------------------------------------------------
    | MAIL_CODE attempts ban seconds
    |--------------------------------------------------------------------------
    |
    | Seconds of ban when no attempts left (default 10 minutes).
    |
    */

    'attempts_ban_seconds' => env('MAIL_CODE_ATTEMPTS_BAN_SEOCNDS', 600)?:0,

];
