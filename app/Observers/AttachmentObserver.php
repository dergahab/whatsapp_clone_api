<?php

namespace App\Observers;

use App\Events\SidebarEvent;
use App\Models\Attachments;
use App\Models\Chat\Message;
use App\Services\Sidebar\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\User;
class AttachmentObserver
{
    public function __construct(public SidebarService $sidebarService) {}
    public function created(Attachments $attachments): void
    {
        $attachment = Attachments::where('uuid', $attachments->uuid)
            ->with(['message.chat.userOne', 'message.chat.userTwo', 'message.chat.unread_messages', 'message.group.receivers'])
            ->first();
        Log::info($attachment);
        $chatReceivers = $attachment?->message?->chat
            ? [$attachment->message->chat->userTwo?->uuid, $attachment->message->chat->userOne?->uuid]
            : [];

        Log::info($chatReceivers);
        $groupReceivers = $attachment?->message?->group?->receivers->pluck('uuid')->toArray() ?? [];
        Log::info($groupReceivers);

        $receiverUuids = collect([...$chatReceivers, ...$groupReceivers])->unique()->values();
        Log::info($receiverUuids);
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
