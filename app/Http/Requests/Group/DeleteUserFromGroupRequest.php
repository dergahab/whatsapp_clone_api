<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;
use App\Models\User;

class DeleteUserFromGroupRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(Group::class).',uuid'],
            'user_id' => ['required', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'user_id' => rp_uuid_to_id(User::class, $this->input('user_id')),
        ]);
    }

    public function validationData()
    {
        $data = $this->all();

        return $data;
    }
}
