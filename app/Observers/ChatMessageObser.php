<?php

namespace App\Observers;

use App\Events\ChatNewMessageSendedEvent;
use App\Events\NotificationEvent;
use App\Events\SidebarEvent;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Models\Chat\Message;
use App\Models\User;
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
	    $message = Message::where("uuid", $message->uuid)->with(['chat.receiver' ,'chat.unread_messages', 'group.receivers'])->first();

        $chatReceivers = [$message?->chat?->receiver?->uuid] ?? [];
        $groupReceivers=$message?->group?->receivers->pluck('uuid')->toArray() ?? [];
        $authUuid=Auth::user()?->uuid;
	    $receiverUuids = collect([...$chatReceivers, ...$groupReceivers, $authUuid])->unique()->values();

	    Log::alert("------------------");

	    foreach ($receiverUuids as $receiverUuid) {
		    $user = User::where('uuid', $receiverUuid)->first();
		    if (!$user) {
			    continue;
		    }
		    // Müvəqqəti həmin istifadəçi ilə "login"
		    Auth::setUser($user);
		    $userId = $user->id; // <-- Düzgün ID tapılır

		    $sidebarData = $this->sidebarService->index(new SearchRequest(),$userId);
			Log::alert(json_encode($sidebarData));
	        event(new SidebarEvent($sidebarData,$receiverUuid));
		}

    }
}
