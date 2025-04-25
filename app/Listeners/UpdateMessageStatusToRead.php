<?php

namespace App\Listeners;

use App\Events\ChatNewMessageSendedEvent;
use App\Models\Chat\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateMessageStatusToRead
{
    public function handle(ChatNewMessageSendedEvent $event): void
    {
        if (isset($event->data['uuid'])) {
            Message::where('uuid', $event->data['uuid'])->update([
                'status' => 2,
            ]);
        }
    }
}
