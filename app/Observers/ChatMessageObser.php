<?php

namespace App\Observers;

use App\Events\ChatNewMessageSendedEvent;
use App\Events\NotificationEvent;
use App\Events\SidebarEvent;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\Chat\Message;
use App\Services\Sidebar\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatMessageObser
{
   public function __construct(public SidebarService $sidebarService)
   {
   }
    public function created(Message $message): void
    {
        $message = $message->load('chat.receiver' ,'chat.unread_messages', 'group.receivers');
        Log::alert($message->chat?->unread_messages);
        $chatReceivers = [$message?->chat?->receiver?->uuid] ?? [];
        $groupReceivers=$message?->group?->receivers->pluck('uuid')->toArray() ?? [];
        $authUuid=Auth::user()?->uuid;
        $receiverUuids = [...$chatReceivers, ...$groupReceivers, ...[$authUuid]];
        event(new SidebarEvent($this->sidebarService->index(new SearchRequest()),$receiverUuids));
    }
}
