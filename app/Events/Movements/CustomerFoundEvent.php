<?php

namespace App\Events\Movements;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerFoundEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $driverName;
    protected $customerName;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct($driverName, $customerName, $message = null)
    {
        $this->driverName = $driverName;
        $this->customerName = $customerName;
        $this->message = $message ?? __('Customer-Found').' '.$customerName;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('foundCustomer.'.getAdminId());
    }

    public function broadcastWith():array
    {
        return [
            'driver' => $this->driverName ,
            'customer' => $this->customerName ,
            'message' => $this->message
        ];
    }

    public function broadcastAs(): string
    {
        return 'foundCustomer';
    }
}
