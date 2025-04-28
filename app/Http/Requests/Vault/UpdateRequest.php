<?php
namespace App\Http\Requests\Vault;

use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'credential' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:50'],
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
