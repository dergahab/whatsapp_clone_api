<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function validatedData()
    {
        $validated = $this->validated();
        $validated['password'] = Hash::make($this->input('password'));

        return $validated;
    }
}
