<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;

class ShowRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(Group::class).',uuid'],
        ];
    }
}
