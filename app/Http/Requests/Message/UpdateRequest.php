<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Message;


class UpdateRequest extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        if (! is_null($this->route('uuid'))) {
            $this->merge([
                'uuid' => $this->route('uuid'),
            ]);
        }
    }
    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid|exists:'.rp_get_table(Message::class).',uuid',
            'message' => 'required|string|max:255|min:1',
        ];
    }
    public function validatedData()
    {
        return collect($this)->only(app(Message::class)->getFillable())->all();
    }
}
