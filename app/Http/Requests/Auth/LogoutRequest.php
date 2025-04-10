<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if (! is_null($this->route('uuid'))) {
            $this->merge([
                'uuid' => $this->route('uuid'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'uuid',
                'exists:' . rp_get_table(User::class) . ',uuid',
            ],
        ];
    }
}
