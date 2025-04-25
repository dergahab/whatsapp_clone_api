<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;

class MessageSentNotification extends Notification
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
