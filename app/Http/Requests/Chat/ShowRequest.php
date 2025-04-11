<?php

namespace App\Http\Requests\Chat;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Chat;

class ShowRequest extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        if (! is_null($this->route('uuid'))) {
            $this->merge([
                'uuid' => $this->route('uuid'),
                'page' => $this->route('page'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', 'exists:'.rp_get_table(Chat::class).',uuid'],
        ];
    }
}
