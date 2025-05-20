<?php

namespace App\Observers;

use App\Events\SidebarEvent;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\Chat\Message;
use App\Models\User;
use App\Services\Sidebar\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatMessageObserver
{
    public function __construct(public SidebarService $sidebarService) {}

    public function created(Message $message): void
    {
        Log::info('Authenticated User:', ['user' => Auth::user()]);
        $message = Message::where('uuid', $message->uuid)->with(['chat.userOne', 'chat.userTwo', 'chat.unread_messages', 'group.receivers'])->first();
        $chatReceivers = [$message?->chat?->userTwo?->uuid ,$message?->chat?->userOne?->uuid] ?? [];
        $groupReceivers = $message?->group?->receivers->pluck('uuid')->toArray() ?? [];
        $receiverUuids = collect([...$chatReceivers, ...$groupReceivers])->unique()->values();
        foreach ($receiverUuids as $receiverUuid) {
            $user = User::where('uuid', $receiverUuid)->first();
            if (! $user) {
                continue;
            }
            Auth::setUser($user);
            $userId = $user->id;
            $sidebarData = $this->sidebarService->index(new SearchRequest, $userId);
            Log::alert(json_encode($sidebarData));
            event(new SidebarEvent($sidebarData, $receiverUuid));
        }
    }
}
