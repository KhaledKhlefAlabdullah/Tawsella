<?php

namespace App\Events\Locations;

use App\Models\Taxi;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetTaxiLocationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $receiver;
    protected Taxi $taxi;
    protected array $path;

    /**
     * Create a new event instance.
     */
    public function __construct(Taxi $taxi, ?User $receiver, ?array $path)
    {
        $this->receiver = $receiver;
        $this->taxi = $taxi;
        $this->path = $path;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return PrivateChannel[]
     */
    public function broadcastOn()
    {
        $channels = [];
        if ($this->receiver) {
            $channels[] = new PrivateChannel('TaxiLocation.' . $this->receiver->id);
        }

        $channels[] = new PrivateChannel('TaxiLocation.' . getAdminId());

        return $channels;
    }

    public function broadcastWith(): array
    {
        $data = [
            'driver_id' => $this->taxi->driver_id,
            'lat' => $this->taxi->last_location_latitude,
            'long' => $this->taxi->last_location_longitude
        ];

        if ($this->path) {
            $data = array_merge($data, ['path' => $this->path]);
        }

        return $data;
    }


    public function broadcastAs()
    {
        return 'TaxiLocation';
    }
}
