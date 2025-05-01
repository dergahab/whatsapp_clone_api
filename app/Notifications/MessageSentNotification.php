<?php

namespace App\Notifications;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
class MessageSentNotification extends Notification implements ShouldBroadcast
{
    private $message;
    private $receivers;

    public function __construct($message, $receivers)
    {
        $this->message = $message;
        $this->receivers = $receivers;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }

    public function broadcastOn()
    {
        $channels = [];

        foreach ($this->receivers as $receiver) {
            $channels[] = new PrivateChannel('notification.' . $receiver);
        }

        return $channels;
    }
}
