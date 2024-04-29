<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MovementFindUnFindEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    protected $driverName;
    protected $customerName;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct($driverName, $customerName, $message)
    {
        $this->driverName = $driverName;
        $this->customerName = $customerName;
        $this->message = $message;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('movemnt.'.getAdminId());
    }

    public function broadcastWith():array
    {
        return [
            'driver' => $this->driverName ,
            'customer' => $this->customerName ,
            'message' => $this->message
        ];
    }
}
