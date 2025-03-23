<?php

namespace App\Http\Requests\Chats;

use Illuminate\Foundation\Http\FormRequest;

class SendPrivateMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'from_user_uuid' => 'required|uuid|exists:users,uuid',
            'to_user_uuid' => 'required|uuid|exists:users,uuid',
            'message' => 'required|string|max:1000',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
