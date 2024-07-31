<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontEndURL = config('app.frontend_url');
        $resetUrl = $frontEndURL . '/password-reset?' . http_build_query([
            'token' => $this->token,
            'email' => $this->email,
        ]);

        return $this->markdown('mail.password.reset')
            ->with('resetUrl', $resetUrl);
    }
}
