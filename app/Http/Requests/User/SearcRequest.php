<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SearcRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
        ];
    }
}
