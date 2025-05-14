<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;
use App\Models\User;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'users' => ['required', 'array'],
            'users.*' => ['required', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'users' => $this->input('users') ? collect($this->input('users'))->map(function ($item) {
                return [
                    'user_id' => rp_uuid_to_id(User::class, $item ?? null) ?? null,
                ];
            })->toArray() : null,
        ]);
    }

    public function groupUsersData()
    {
        $users = $this->input('users', []);
        $users[] = [
            'user_id' => auth()->user()->id,
        ];

        return $users;
    }

    public function groupData()
    {
        return $this->only(app(Group::class)->getfillable());
    }
}
