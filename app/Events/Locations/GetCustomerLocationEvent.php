<?php

namespace App\Events\Locations;

use App\Models\User;
use Google\Service\Drive\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetCustomerLocationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $customer;
    protected float $latitude;
    protected float $longitude;

    /**
     * Create a new event instance.
     */
    public function __construct(User $customer, float $lat, float $lon)
    {
        $this->customer = $customer;
        $this->latitude = $lat;
        $this->longitude = $lon;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('customerLocation.'. $this->customer->id);
    }

    /**
     * @return array of broadcasting data
     */
    public function broadcastWith()
    {
        return [
            'customer_name' => $this->customer->profile?->name,
            'customer_avatar' => $this->customer->profile?->avatar,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
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
