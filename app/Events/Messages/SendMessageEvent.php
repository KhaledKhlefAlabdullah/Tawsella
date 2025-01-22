<?php

namespace App\Events\Messages;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $chat_id;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chat_id)
    {
        $this->message = $message;
        $this->chat_id = $chat_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('send-message.'.$this->chat_id)
        ];
    }

    public function broadCastWith(){
        return $this->message;
    }

    public function broadcastAs()
    {
        return 'sendMessage';
    }
}
