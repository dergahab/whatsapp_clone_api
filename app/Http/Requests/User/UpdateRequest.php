<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ];
    }


}
