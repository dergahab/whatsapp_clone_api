<?php

namespace App\Http\Requests\Vault;

use App\Http\Requests\BaseRequest;
use App\Models\Vault\Password;

class DestroyRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'uuid',
                'exists:' . rp_get_table(Password::class) . ',uuid',
            ],
        ];
    }
}
