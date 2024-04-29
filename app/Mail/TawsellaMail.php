<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TawsellaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_message;

    /**
     * Create a new message instance.
     */
    public function __construct($mail_message)
    {
        $this->mail_message = $mail_message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tawsella Mails',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // todo we have to customize maile view
        return new Content(
            view: 'mail.mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
