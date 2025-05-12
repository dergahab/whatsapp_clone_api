<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ListRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}
