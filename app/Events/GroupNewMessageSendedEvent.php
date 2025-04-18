<?php

namespace App\Events;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupNewMessageSendedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $groupUuid;

    public function __construct($data, $groupUuid)
    {
        $this->data = $data;
        $this->groupUuid = $groupUuid;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('message.' . $this->groupUuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'text';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
