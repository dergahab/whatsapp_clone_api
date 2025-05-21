<?php
namespace App\Observers;

use App\Events\SidebarEvent;
use App\Models\Chat\Message;
use App\Models\User;
use App\Services\Sidebar\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatMessageObserver
{
	public function __construct(public SidebarService $sidebarService)
	{
	}

	public function created(Message $message): void
	{
		$this->dispatchSidebarUpdateForMessage($message, 'created');
	}

	public function updated(Message $message): void
	{
		$this->dispatchSidebarUpdateForMessage($message, 'updated');
	}

	public function deleted(Message $message): void
	{
		$this->dispatchSidebarUpdateForMessage($message, 'deleted');
	}

	/**
	 * Dispatch sidebar update for all relevant users when a message is changed.
	 */
	private function dispatchSidebarUpdateForMessage(Message $message, string $action): void
	{
		$message = Message::where('uuid', $message->uuid)
			->with(['chat.receiver', 'chat.unread_messages', 'group.receivers'])
			->first();

		if (!$message) {
			Log::warning("Message not found for UUID: {$message->uuid}");
			return;
		}

		$chatReceivers = [$message->chat?->receiver?->uuid] ?? [];
		$groupReceivers = $message->group?->receivers?->pluck('uuid')->toArray() ?? [];
		$authUuid = Auth::user()?->uuid;
		$receiverUuids = collect([...$chatReceivers, ...$groupReceivers, $authUuid])
			->filter()
			->unique()
			->values();

		Log::info("Dispatching sidebar update for message action '{$action}' for UUIDs: " . implode(', ', $receiverUuids->toArray()));

		foreach ($receiverUuids as $receiverUuid) {
			$user = User::where('uuid', $receiverUuid)->first();

			if (!$user) {
				Log::warning("User not found with UUID: {$receiverUuid}");
				continue;
			}

			// Temporarily impersonate user
			Auth::setUser($user);

			$userId = $user->id;

			// If your SidebarService::index depends on SearchRequest, refactor it to use a simple method like this:
			$sidebarData = $this->sidebarService->getSidebarDataForUser($userId); // ← You need to implement this method

			Log::info("Sidebar data prepared for user {$userId}: " . json_encode($sidebarData));

			event(new SidebarEvent($sidebarData, $receiverUuid));
		}
	}
}
