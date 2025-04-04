<?php

namespace App\Http\Requests\Message;

use App\Models\Chat\Chat;
use Illuminate\Foundation\Http\FormRequest;

class ShowAllMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => ['required', 'uuid', 'exists:'.rp_get_table(Chat::class).',uuid'],
            'page' => ['nullable', 'integer'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'chat_id' => rp_uuid_to_id(Chat::class, $this->input('chat_id'))
        ]);
    }
}
