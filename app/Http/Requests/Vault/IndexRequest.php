<?php

namespace App\Http\Requests\Vault;

use App\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer'],
        ];
    }
}
