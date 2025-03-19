<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{


    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
