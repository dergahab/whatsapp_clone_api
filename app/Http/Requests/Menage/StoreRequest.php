<?php

namespace App\Http\Requests\Menage;

use App\Http\Requests\BaseRequest;
use App\Models\Vault\Password;
use App\Models\User;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required',"uuid", 'exists:' . rp_get_table(User::class) . ',uuid'],
            'credentials' => ['required', 'array', 'min:1'],
            'credentials.*.password_id' => ['required', 'uuid', 'distinct', 'exists:' . rp_get_table(Password::class) . ',uuid'],
        ];
    }

    public function validatedData(): array
    {
        return $this->only(app(User::class)->getfillable());
    }

    public function passedValidation()
    {
        $this->merge([
            'user_id' => $this->input('user_id'),
            'credentials' => collect( $this->input('credentials'))->map(function ($credential) {

                return [
                    'password_id' => rp_uuid_to_id(Password::class, $credential['password_id']),
                ];
            })->toArray(),
        ]);
    }

    public function credentialsData(): array
    {
        return $this->credentials;
    }
}
