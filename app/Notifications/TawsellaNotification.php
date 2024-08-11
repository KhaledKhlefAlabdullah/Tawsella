<?php

namespace App\Notifications;

use App\Mail\TawsellaMail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TawsellaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user_profile;
    protected $message;
    protected $viaChannels;
    protected $receivers;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_profile, string $message, $receivers, array $viaChannels = ['database'])
    {
        $this->viaChannels = $viaChannels;
        $this->user_profile = $user_profile;
        $this->message = $message;
        $this->receivers = is_array($receivers) ? $receivers : [$receivers];
    }

    public function via($notifiable)
    {
        return ['broadcast', ...$this->viaChannels];
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender_name' => $this->user_profile->name ?? 'Anonymous',
            'sender_image' => $this->user_profile ? $this->user_profile->avatar_url : null,
            'message' => __($this->message),
        ];
    }

    public function toMail($notifiable)
    {
        try {
            $emails = [];
            foreach ($this->receivers as $receiver) {
                if (is_object($receiver) && property_exists($receiver, 'email')) {
                    $emails[] = $receiver->email;
                } else {
                    throw new \Exception('Receiver must be an object with an email property');
                }
            }

            if (!empty($emails)) {
                $success = send_mail($this->message, $emails);

                if ($success) {
                    return (new TawsellaMail($this->message))->to($notifiable);
                }
            } else {
                throw new \Exception('No valid email addresses found');
            }
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage());

            return api_response(errors: [$e->getMessage()], message: 'Could not send the email');
        }

        return api_response(message: 'Could not send the email', code: 500);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_name' => $this->user_profile->name ?? 'Anonymous',
            'sender_image' => $this->user_profile ? $this->user_profile->avatar_url : null,
            'message' => __($this->message),
        ]);
    }

    /**
     * Select the channel whill broadcaston
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->receivers as $receiver) {
            $channels[] = new Channel('Notification-to-user-id' . $receiver->id);
        }
        return $channels;
    }

    public function broadcastAs(){
        return 'Notifications';
    }

    
    public function toArray($notifiable)
    {
        return [
            // Additional data can be added here
        ];
    }
}
