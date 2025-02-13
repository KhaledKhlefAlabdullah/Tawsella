<?php

namespace App\Notifications;

use App\Mail\StarTaxiMail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class StarTaxiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $viaChannels;
    protected $receiver;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $receiver, array $viaChannels = ['database'])
    {
        $this->viaChannels = $viaChannels;
        $this->message = $message;
        $this->receiver = $receiver;
    }

    public function via($notifiable)
    {
        return array_unique(array_merge($this->viaChannels, ['broadcast']));
    }

    public function toDatabase($notifiable)
    {
        return  $this->message;
    }

    public function toMail($notifiable)
    {
        try {
            $emails = [];
            foreach ($this->receiver as $receiver) {
                if (is_object($receiver) && property_exists($receiver, 'email')) {
                    $emails[] = $receiver->email;
                } else {
                    throw new \Exception('Receiver must be an object with an email property');
                }
            }

            if (!empty($emails)) {
                $success = send_mail($this->message, $emails);

                if ($success) {
                    return (new StarTaxiMail($this->message))->to($notifiable);
                }
            } else {
                throw new \Exception('No valid email addresses found');
            }
        } catch (\Exception $e) {
            Log::error('Email sending error: ' .  [$e->getMessage()]);

            return api_response(message: 'Could not send the email', errors: [$e->getMessage()]);
        }

        return api_response(message: 'Could not send the email', code: 500);
    }

    public function toArray($notifiable)
    {
        return [
            // Additional data can be added here
        ];
    }
}
