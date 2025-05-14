<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => [
                'required',
                'uuid',
                Rule::exists(rp_get_table(Chat::class), 'uuid')->whereNull('deleted_at'),
            ],
            'message' => [
                Rule::requiredIf(function () {
                    return ! $this->hasFile('file');
                }),
            ],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function passedValidation(): void
    {
        $this->merge([
            'chat_id' => rp_uuid_to_id(Chat::class, $this->input('chat_id')) ?? null,
            'create_by' => Auth::id(),
        ]);
    }

    public function validationData()
    {
        return $this->only(app(Message::class)->getfillable());
    }
}
