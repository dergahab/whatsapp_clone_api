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
    public function toArray(Request $request): array
    {
	    return [
		    'uuid' => $this->uuid,
		    "type" => 'group',
		    'unread_count' => $this->unread_count ?? 0,
		    'message' => new MessageResource($this->message),
		    'send_by' => new UserResource($this->sendBy),
	    ];
    }
}
