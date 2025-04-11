<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

	public function toArray($request): array
	{
		$message = $this->message ? new MessageResource($this->message) : null;
		$messageArray = $message ? $message->toArray($request) : [];

		return [
			'uuid' => $this->uuid,
			'type' => 'group',
			'name' => $this->name,
			'unread_count' => $this->unread_count ?? 0,
			'image' => $this->file,

			'message_text' => $messageArray['message'] ?? null,
			'message_status' => $messageArray['status'] ?? null,
			'message_edit_status' => $messageArray['edit_status'] ?? null,
			'message_time' => $messageArray['created_at'] ?? null,
			'message_create_by' => $messageArray['create_by'] ?? null,

			'send_by' => $this->sendBy ? new UserResource($this->sendBy) : null,
		];
	}
}
