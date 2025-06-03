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
        $this->handleSidebarUpdate($message);
    }

    public function updated(Message $message): void
    {
        $this->handleSidebarUpdate($message);
    }

    public function deleted(Message $message): void
    {
        $this->handleSidebarUpdate($message);
    }

    private function handleSidebarUpdate(Message $message): void
    {
        // Fully reload the message and all needed relationships
        $message = Message::with(['chat.receiver', 'chat.unread_messages', 'group.receivers'])
            ->where('uuid', $message->uuid)
            ->firstOrFail();

        $chatReceiverUuid = $message->chat?->receiver?->uuid;
        $groupReceiverUuids = $message->group?->receivers->pluck('uuid')->toArray() ?? [];
        $currentUser = Auth::user();

        if (! $currentUser) {
            \Log::warning('No authenticated user for sidebar update.');

            return;
        }

        $receiverUuids = collect([$chatReceiverUuid, ...$groupReceiverUuids, $currentUser->uuid])
            ->filter()
            ->unique()
            ->values();

        foreach ($receiverUuids as $uuid) {
            $user = User::where('uuid', $uuid)->first();
            if (! $user) {
                continue;
            }

            try {
                Log::debug("Sidebar update for user ID: {$user->id}");

                $sidebarData = $this->sidebarService->index(new SearchRequest, $user->id);

                Log::debug('Sidebar data generated:', $sidebarData);

                event(new SidebarEvent($sidebarData, $uuid));
            } catch (\Throwable $e) {
                Log::error("Sidebar update failed for {$uuid}", ['error' => $e->getMessage()]);
            }
        }
    }
}
