<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use Illuminate\Validation\Rule;

class ShowRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(Message::class).',uuid' ,Rule::exists(rp_get_table(Message::class), 'uuid')->whereNull('deleted_at')],
        ];
    }
}
