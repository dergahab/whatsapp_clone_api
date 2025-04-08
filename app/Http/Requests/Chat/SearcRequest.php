<?php

namespace App\Http\Requests\Chat;

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
