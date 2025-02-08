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

    protected Taxi $taxi;
    protected $path = null;

    /**
     * Create a new event instance.
     */
    public function __construct(Taxi $taxi, $path = null)
    {
        $this->taxi = $taxi;
        $this->path = $path;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('TaxiLocation.' . $this->taxi->driver_id);
    }

    public function broadcastWith(): array
    {
        $driver = User::with(['profile'])->where('id',$this->taxi->driver_id)->first();
        $data = [
            'driver_id' => $driver->id,
            'driver_name' => $driver->profile->name,
            'driver_avatar' => $driver->profile->avatar,
            'lat' => $this->taxi->last_location_latitude,
            'long' => $this->taxi->last_location_longitude
        ];

        if (!is_null($this->path)) {
            $data = array_merge($data, ['path' => $this->path]);
        }

        return $data;
    }


    public function broadcastAs()
    {
        return 'TaxiLocation';
    }
}
