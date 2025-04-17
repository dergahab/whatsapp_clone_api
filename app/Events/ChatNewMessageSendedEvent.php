<?php

namespace App\Events;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatNewMessageSendedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $chatUuid;

    public function __construct($data, $chatUuid)
    {
        $this->data = $data;
        $this->chatUuid = $chatUuid;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('message.' . $this->chatUuid),
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
