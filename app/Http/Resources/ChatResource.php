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
		return [
			'uuid' => $this->uuid,
			"type" => 'chat',
			'unread_count' => $this->unread_count ?? 0,
			'message' => new MessageResource($this->message),
			'send_by' => new UserResource($this->sendBy),
		];
	}
}