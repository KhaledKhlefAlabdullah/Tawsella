<?php

namespace App\Events\Locations;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetCustomerLocationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $driver;
    protected $customer;

    /**
     * Create a new event instance.
     */
    public function __construct(User $customer, User $driver)
    {
        $this->driver = $driver;
        $this->customer = $customer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer-location.' . $this->driver->id),
        ];
    }

    /**
     * @return array of broadcasting data
     */
    public function broadcastWith()
    {
        return [
            'driver_name' => $this->customer->profile->name,
            'driver_avatar' => $this->customer->profile->avatar,
            'latitude' => $this->customer->last_location_latitude,
            'longitude' => $this->customer->last_location_longitude
        ];
    }

    /**
     * @return string Channel name to listen in frontend
     */
    public function broadcastAs()
    {
        return 'getCustomerLocationEvent';
    }
}
