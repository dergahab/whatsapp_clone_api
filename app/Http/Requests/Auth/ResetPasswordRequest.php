<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ResetPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|numeric',
            'new_password' => 'required|min:6',
        ];
    }
}
