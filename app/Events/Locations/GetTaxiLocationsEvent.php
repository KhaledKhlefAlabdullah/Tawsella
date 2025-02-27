<?php

namespace App\Events\Locations;

use App\Enums\UserEnums\UserType;
use App\Models\Taxi;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetTaxiLocationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Taxi $taxi;
    protected $path = null;
    protected $driversLocations = null;

    /**
     * Create a new event instance.
     */
    public function __construct(Taxi $taxi, $path = null)
    {
        $this->taxi = $taxi;
        $this->path = $path;
        $this->driversLocations = $this->getDriversLocations();
    }

    public function getDriversLocations(){
        $drivers = User::with(['taxi', 'profile'])
            ->role(UserType::TaxiDriver()->key)
            ->where([
                'is_active' => true
            ])
            ->has('taxi') // Ensure the user has a related taxi
            ->get();

        return $drivers->map(function ($driver) {
            return [
                'driver_id' => $driver->id,
                'name' => $driver->profile?->name,
                'avatar' => $driver->profile?->avatar,
                'lat' => $driver->taxi?->last_location_latitude ?? null,
                'long' => $driver->taxi?->last_location_longitude ?? null,
            ];
        });
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

        if(!is_null($this->driversLocations)) {
            $data = array_merge($data, ['drivers_locations' => $this->driversLocations]);
        }
        return $data;
    }


    public function broadcastAs()
    {
        return 'TaxiLocation';
    }
}
