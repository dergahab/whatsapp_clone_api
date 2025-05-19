<?php

namespace App\Observers;

use App\Events\SidebarEvent;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\Attachments;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatFileObser
{
    public function created(Attachments $attachments): void
    {
        $message= Attachments::where('uuid',$attachments->uuid)->whith(['messages.chat.receiver','messages.chat.unread_messages','messages.group.receivers'])->first();

//        Log::info($message);
//        $chatReceivers = [$message?->chat?->receiver?->uuid] ?? [];
//        $groupReceivers = $message?->group?->receivers->pluck('uuid')->toArray() ?? [];
//        $authUuid = Auth::user()?->uuid;
//        $receiverUuids = collect([...$chatReceivers, ...$groupReceivers, $authUuid])->unique()->values();
//        foreach ($receiverUuids as $receiverUuid) {
//            $user = User::where('uuid', $receiverUuid)->first();
//            if (! $user) {
//                continue;
//            }
//            Auth::setUser($user);
//            $userId = $user->id;
//            $sidebarData = $this->sidebarService->index(new SearchRequest, $userId);
//            Log::alert(json_encode($sidebarData));
//            event(new SidebarEvent($sidebarData, $receiverUuid));
//        }


    }

}
