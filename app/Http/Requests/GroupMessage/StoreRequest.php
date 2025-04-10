<?php

namespace App\Http\Requests\GroupMessage;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;
use App\Models\Chat\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            "group_id" =>[ "nullable", "uuid", "exists:".rp_get_table(Group::class).",uuid"],
	        "message" => ["required", "string"],
        ];
    }

	public function passedValidation()
	{
		$this->merge([
			'group_id' => rp_uuid_to_id(Group::class, $this->input('group_id')),
			"create_by" => Auth::user()?->id,
		]);
	}


}
