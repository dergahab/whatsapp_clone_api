<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;

class IndexRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['nullable', 'uuid', 'exists:'.rp_get_table(Group::class).',uuid'],
            'search' => ['nullable', 'string'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'group_id' => rp_uuid_to_id(Group::class, $this->input('uuid')),
        ]);
    }

    public function validationData()
    {
        $data = $this->all();

        return $data;
    }
}
