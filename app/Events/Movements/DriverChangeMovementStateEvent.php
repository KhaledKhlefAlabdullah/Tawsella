<?php

namespace App\Events\Movements;

use App\Models\TaxiMovement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverChangeMovementStateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    protected TaxiMovement $taxiMovement;
    protected $state;

    /**
     * Create a new event instance.
     */
    public function __construct(TaxiMovement $taxiMovement, $state)
    {
        $this->taxiMovement = $taxiMovement;
        $this->state = $state;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn(): Channel
    {
        return ['found-customer.' . getAdminId()];
    }

    public function broadcastWith(): array
    {
        $driverName = $this->taxiMovement->driver->profile?->name;
        $customerName = $this->taxiMovement->customer->profile?->name;
        $from = $this->taxiMovement->start_address;
        $to = $this->taxiMovement->destination_address;

        return [
            'driver' => $driverName,
            'customer' => $customerName,
            'message' => $this->getMessage($driverName, $customerName, $from, $to),
        ];
    }

    protected function getMessage($driverName, $customerName, $from, $to)
    {
        if ($this->state == 'completed-movement') {
            return __('driver') . ' ' . $driverName . ' ' . __('complete-movement') . ' ' . __('from') . ' ' . $from . ' ' . __('to') . ' ' . $to;

        }

        return $this->state ?
            __('driver') . ' ' . $driverName . ' ' . __('find') . ' ' . __('customer') . ' ' . $customerName :
            __('driver') . ' ' . $driverName . ' ' . __('don\'t-find') . ' ' . __('customer') . ' ' . $customerName;
    }

    public function broadcastAs(): string
    {
        return 'foundCustomer';
    }
}
