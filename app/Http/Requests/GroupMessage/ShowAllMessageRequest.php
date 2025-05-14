<?php

namespace App\Http\Requests\GroupMessage;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;

class ShowAllMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'uuid', 'exists:'.rp_get_table(Group::class).',uuid'],
            'page' => ['nullable', 'integer'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'group_id' => rp_uuid_to_id(Group::class, $this->input('group_id')),
        ]);
    }
}
