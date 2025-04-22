<?php

namespace App\Http\Requests\Vault;

use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\BaseRequest;

class StorePasswordRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'credential' => 'required|array',
            'title' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:800',
        ];
    }

    public function validatedData()
    {
        $validated = $this->validated(); //Crypt::encrypt
        $validated['credential'] = Crypt::encrypt($this->input('credential'));

        return $validated;
    }
}
