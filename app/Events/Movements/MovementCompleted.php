<?php

namespace App\Events\Movements;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MovementCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $driver;
    protected User $customer;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct($driver, $customer, $message = null)
    {
        $this->driver = $driver;
        $this->customer = $customer;
        $this->message = $message;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('movementCompleted.'.getAdminId());
    }

    public function broadcastWith():array
    {
        return [
            'driver' => [
                'name'=>$this->driver->profile->name,
                'profile' => $this->driver->profile->avatar
            ] ,
            'customer' => [
                'name'=>$this->customer->profile->name,
                'profile' => $this->customer->profile->avatar
            ] ,
            'message' => $this->message
        ];
    }

    public function broadcastAs(): string
    {
        return 'movementCompleted';
    }
}
