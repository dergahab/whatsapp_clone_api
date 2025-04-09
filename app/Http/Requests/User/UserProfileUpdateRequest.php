<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;


class UserProfileUpdateRequest extends FormRequest
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
            'uuid' => ['required', 'string', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ];
    }

    public function passedValidation()
    {
        $id = rp_uuid_to_id(User::class, $this->input('uuid'));

        $this->merge([
            'id' => $id,
        ]);
    }

    public function validatedData(): array
    {
        return $this->only([
            'id', 'name', 'email', 'profile_picture'
        ]);
    }

}
