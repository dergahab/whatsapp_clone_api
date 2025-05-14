<?php

namespace App\Http\Requests\GroupMessage;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;
use App\Models\Chat\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'uuid', 'exists:'.rp_get_table(Group::class).',uuid'],
            'message' => [
                Rule::requiredIf(function () {
                    return ! $this->hasFile('file');
                }),
            ],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'group_id' => rp_uuid_to_id(Group::class, $this->input('group_id')),
            'create_by' => Auth::id(),
        ]);
    }

    public function validationData()
    {
        return $this->only(app(Message::class)->getfillable());
    }
}
