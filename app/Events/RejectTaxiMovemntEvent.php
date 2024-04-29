<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RejectTaxiMovemntEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $customer_id;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct(string $customer_id, string $message)
    {
        $this->customer_id = $customer_id;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {   
        $customer_id = $this->customer_id;
        return new PrivateChannel('customer.'.$customer_id);
    }

    public function broadcastWith():array
    {
        return [
            'message' => $this->message
        ];
    }
}
