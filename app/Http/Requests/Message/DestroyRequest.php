<?php

namespace App\Http\Requests\Message;

use App\Models\Chat\Message;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    protected function prepareForValidation()
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
            'uuid' => [
                'required',
                'uuid',
                'exists:'.rp_get_table(Message::class).',uuid',
            ],

        ];
    }
}
