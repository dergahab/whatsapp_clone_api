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
            'email' => 'required|string|email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required',
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->input('password')),
        ]);
    }
}
