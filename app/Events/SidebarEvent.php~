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
    public $receivers;

    public function __construct($sidebar,$receivers)
    {
        $this->sidebar = $sidebar;
        $this->receivers = $receivers;

    }

    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->receivers as $receiver) {
            $channels[] = new Channel('sidebar.' .$receiver );
        }
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

