<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SidebarEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sidebar;

    public $receiver;

    public function __construct($sidebar, $receiver)
    {
        $this->sidebar = $sidebar;
        $this->receiver = $receiver;

    }

    public function broadcastOn()
    {
        $channels = [];

        $channels[] = new Channel('sidebar.'.$this->receiver);

        return $channels;
    }

    public function broadcastAs()
    {
        return 'new-sidebar';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => $this->sidebar,
        ];
    }
}
