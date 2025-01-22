<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class test implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        Log::info('Event Test dispatched with message: ' . $message);
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('test');
    }

    public function broadcastAs(): string
    {
        return 'test';
    }
}
