<?php

namespace App\Events\Movements;

use App\Models\TaxiMovement;
use Google\Service\AppHub\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverChangeMovementStateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $driverName;
    protected string $customerName;
    protected string $message;

    /**
     * Create a new event instance.
     */
    public function __construct(string $driverName, string $customerName, string $message)
    {
        $this->driverName = $driverName;
        $this->customerName = $customerName;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('foundCustomer.' . getAdminId());
    }

    public function broadcastWith(): array
    {
        return [
            'driver' => $this->driverName,
            'customer' => $this->customerName,
            'message' => $this->message,
        ];
    }

    public function broadcastAs(): string
    {
        return 'foundCustomer';
    }
}
