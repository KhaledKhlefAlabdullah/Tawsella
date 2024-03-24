<?php

namespace App\Events;

use App\Models\TaxiMovement;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptTaxiMovemntEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $taxiMovement;
    
    /**
     * Create a new event instance.
     */
    public function __construct(TaxiMovement $taxiMovement)
    {
        $this->taxiMovement = $taxiMovement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {   
        $customer_id = $this->taxiMovement->customer_id;
        $driver_id = $this->taxiMovement->driver_id;

        
        return [
            new PrivateChannel('customer.'.$customer_id),
            new PrivateChannel('driver.'.$driver_id)
        ];
    }

    public function broadcastWith(): array
    {
        $customer_id = $this->taxiMovement->customer_id;
        $driver_id = $this->taxiMovement->driver_id;

        $customer_profile = UserProfile::where('user_id', $customer_id)->select('name','user_avatar','phoneNumber',)->first();
      
        $driver_profile = UserProfile::where('user_id', $customer_id)->select('name','user_avatar','phoneNumber',)->first();

        $customer_data = ['message' => '','driver' => $driver_profile];
        $driver_data = ['customer' => $customer_profile , 'taxiMovementInfo' => $this->taxiMovement];
        return [
            'customer.'.$customer_id => $customer_data,
            'driver.'.$driver_id => $driver_data
        ];
    }
}
