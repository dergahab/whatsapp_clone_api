<?php

namespace App\Http\Requests\Menage;

use App\Http\Requests\BaseRequest;
use App\Models\Vault\UserPassword;
use App\Models\User;


class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', "uuid", 'exists:' . rp_get_table(User::class) . ',uuid'],
            'credentials' => ['required', 'array', 'min:1'],
            'credentials.*.password_id' => ['required', 'uuid', 'distinct', 'exists:' . rp_get_table(UserPassword::class) . ',uuid'],
        ];
    }
}
