<?php

namespace App\Http\Requests\Groups;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToGroupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_uuid' => 'required|uuid|exists:users,uuid',
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
