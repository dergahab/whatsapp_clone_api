<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
		    'status' => $this->status,
		    'edit_status' => (bool) $this->edit_status,
		    'message' => $this->message,
		    'group_id' => $this->group_id,
		    'created_at' => $this->created_at,
		    'create_by' => $this->create_by,
	    ];
    }
}
