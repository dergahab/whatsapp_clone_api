<?php

namespace App\Http\Requests\Vault;

use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\BaseRequest;
use App\Models\Vault\Password;

class StoreRequest extends BaseRequest
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

    public function passedValidation()
    {
        $credential = $this->input('credential') ? json_encode($this->input('credential')) : null;
        $this->merge([
            'credential' => Crypt::encrypt($credential),
        ]);
    }

    public function validatedData()
    {
        return $this->only(app(Password::class)->getfillable());

    }
}
