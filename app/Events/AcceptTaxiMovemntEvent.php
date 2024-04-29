<?php

namespace App\Events;

use App\Models\TaxiMovementType;
use App\Models\UserProfile;
use Illuminate\Broadcasting\PrivateChannel;

class AcceptTaxiMovemntEvent extends BaseEvent
{
    protected $driver_id;
    protected $movement_type;

    /**
     * Create a new event instance.
     */
    public function __construct($taxiMovement)
    {
        $this->driver_id = $taxiMovement->driver_id;
        $this->movement_type = TaxiMovementType::findOrFail($taxiMovement->movement_type_id)->type;

        parent::__construct($taxiMovement);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $customer_id = $this->customer_id;
        $driver_id = $this->driver_id;


        return [
            new PrivateChannel('customer.' . $customer_id),
            new PrivateChannel('driver.' . $driver_id)
        ];
    }

    public function getDriverData()
    {
        return [
            'gender' => $this->gender,
            'customer_address' => $this->customer_address,
            'destnation_address' => $this->customer_destnation_address,
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long,
            'type' => $this->movement_type
        ];
    }

    public function broadcastWithDriver(): array
    {
        $customer_id = $this->customer_id;

        $customer_profile = UserProfile::where('user_id', $customer_id)->select('name', 'avatar', 'phoneNumber',)->first();

        return [
            'request_id' => $this->request_id,
            'customer' => $customer_profile,
            'taxiMovementInfo' => $this->getDriverData()
        ];
    }

    public function broadcastWithCustomer(): array
    {
        $driver_id = $this->driver_id;

        $driver_profile = UserProfile::where('user_id', $driver_id)->select('name', 'avatar', 'phoneNumber',)->first();

        return [
            'message' => '',
            'driver' => $driver_profile
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        $channels = $this->broadcastOn();

        // Check which channel is being broadcasted to
        foreach ($channels as $channel) {
            if ($channel instanceof PrivateChannel && strpos($channel->name, 'customer') !== false) {
                return $this->broadcastWithCustomer();
            } elseif ($channel instanceof PrivateChannel && strpos($channel->name, 'driver') !== false) {
                return $this->broadcastWithDriver();
            }
        }

        // Default data if channel not recognized
        return [];
    }

    public function broadcastAs(){
        return 'accept-request';
    }
}
