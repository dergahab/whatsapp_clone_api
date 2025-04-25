<?php

namespace App\Http\Requests\Menage;

use App\Models\Vault\UserPassword;
use App\Http\Requests\BaseRequest;

class DestroyRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:' . rp_get_table(UserPassword::class) . ',uuid'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
