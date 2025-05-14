<?php

namespace App\Http\Requests\Vault;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Crypt;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'credential' => ['required', 'array'],
            'title' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:800'],
        ];
    }

    public function validatedData()
    {
        $validated = $this->validated();

        if ($this->filled('credential')) {
            $validated['credential'] = Crypt::encrypt($this->input('credential'));
        }

        return $validated;
    }
}
