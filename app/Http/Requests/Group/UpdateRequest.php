<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255', 'unique:groups'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
}
