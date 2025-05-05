<?php

namespace App\Observers;

use App\Events\SidebarEvent;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\Chat\Message;
use App\Services\Sidebar\SidebarService;
use http\Env\Request;
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
        $receiverUuids = [...$chatReceivers, ...$groupReceivers];
        event(new SidebarEvent($this->sidebarService->index(new SearchRequest()),$receiverUuids));
    }
}
