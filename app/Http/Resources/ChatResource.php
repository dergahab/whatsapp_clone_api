<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		$message = $this->message ? new MessageResource($this->message) : null;
		$messageArray = $message ? $message->toArray($request) : [];

		return [
			'uuid' => $this->uuid,
			'type' => 'chat',
			'name' => $this->sendBy->name ?? null,
			'unread_count' => $this?->message?->unread_messages,
			'image' => $this->sendBy->profile_picture ?? null,
            'attachment'=>$this->message?->attachment?->attachment_type,
			'message_text' => $messageArray['message'] ?? null,
			'message_status' => $messageArray['status'] ?? null,
			'message_edit_status' => $messageArray['edit_status'] ?? null,
			'message_time' => $messageArray['created_at'] ?? null,
			'message_create_by' => $messageArray['create_by'] ?? null,
		];
	}
}

