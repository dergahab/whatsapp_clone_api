<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Models\User;

class ShowRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
        ];
    }
}
