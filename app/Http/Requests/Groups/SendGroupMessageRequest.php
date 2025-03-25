<?php

namespace App\Http\Requests\Groups;

use Illuminate\Foundation\Http\FormRequest;

class SendGroupMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'from_group_user_uuid' => 'required|uuid|exists:group_users,uuid',
            'to_group_user_uuid' => 'required|uuid|exists:group_users,uuid',
            'message' => 'required|string|max:1000',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
