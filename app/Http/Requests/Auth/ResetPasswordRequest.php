<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class ResetPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required'],
        ];
    }

    public function validatedData()
    {
        $validated = $this->validated();
        $validated['password'] = Hash::make($this->input('password'));
        return $validated;

    }
}
