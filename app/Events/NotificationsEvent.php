<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $receivers;
    protected $message;

    public function __construct($receivers, $message)
    {
        $this->receivers = $receivers;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $channels = [];
        if (is_array($this->receivers)) {
            foreach ($this->receivers as $receiver) {
                if (is_object($receiver) && property_exists($receiver, 'id')) {
                    $channels[] = new PrivateChannel('Notification-to-user-id' . $receiver->id);
                }
            }
        } else if (is_object($this->receivers) && property_exists($this->receivers, 'id')) {
            $channels[] = new PrivateChannel('Notification-to-user-id' . $this->receivers->id);
        }

        return $channels;
    }

    public function broadcastWith(){
        return ['message' => $this->message];
    }
}
