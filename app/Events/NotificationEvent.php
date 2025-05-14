<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $sender;

    public $receivers;

    public function __construct($message, $receivers)
    {
        $this->message = $message;
        $this->receivers = $receivers;
        $this->sender = str_replace(' ', '_', auth()->user()->name);
    }

    public function broadcastOn(): array
    {
        $channels = [];
        foreach ($this->receivers as $receiver) {
            $channels[] = new Channel('notification.'.$receiver);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'alert';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'message' => $this->message,
                'sender' => $this->sender,
            ],
        ];
    }
}
