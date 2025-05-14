<?php

namespace App\Http\Requests\Menage;

use App\Http\Requests\BaseRequest;
use App\Models\User;

class DestroyRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
