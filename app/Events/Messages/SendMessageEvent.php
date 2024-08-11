<?php

namespace App\Events\Messages;

use App\Events\BaseEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $receiver_id;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $receiver_id)
    {
        $this->message = $message;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('send-message-'.$this->receiver_id),
        ];
    }

    public function broadCastWith(){
        return $this->message;
    }
}
