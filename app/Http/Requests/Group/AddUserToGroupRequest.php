<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\BaseRequest;
use App\Models\Chat\Group;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddUserToGroupRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'group_uuid' => ['required', 'uuid', 'exists:' . rp_get_table(Group::class) . ',uuid'],
            'users' => ['required', 'array'],
            'users.*' => ['required', 'uuid', 'exists:' . rp_get_table(User::class) . ',uuid'],
        ];
    }


    public function passedValidation()
    {
        $this->merge([
            'users' => $this->input('users') ? collect($this->input('users'))->map(function ($item) {
                return [
                    'user_id' => rp_uuid_to_id(User::class, $item ?? null) ?? null,
                ];
            })->toArray(): null,
        ]);
    }
    public function groupUsersData()
    {
        return $this->input('users', []);
    }

}
