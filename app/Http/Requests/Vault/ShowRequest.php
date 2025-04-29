<?php

namespace App\Http\Requests\Vault;

use App\Http\Requests\BaseRequest;
use App\Models\Vault\Password;

class ShowRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:' . rp_get_table(Password::class) . ',uuid'],
        ];
    }
}
