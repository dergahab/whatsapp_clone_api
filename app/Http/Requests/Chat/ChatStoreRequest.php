<?php

namespace App\Http\Requests\Chat;

use App\Http\Requests\BaseRequest;
use App\Models\User;

class ChatStoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user1' => ['required', 'string', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
            'user2' => ['required', 'string', 'uuid', 'exists:'.rp_get_table(User::class).',uuid'],
        ];
    }

    public function passedValidation()
    {
        $user1Id = rp_uuid_to_id(User::class, $this->input('user1'));
        $user2Id = rp_uuid_to_id(User::class, $this->input('user2'));

        if (! $user1Id || ! $user2Id) {
            throw new \Exception('Invalid UUID');
        }

        $this->merge([
            'user1' => $user1Id,
            'user2' => $user2Id,
        ]);
    }

    public function validatedData(): array
    {
        return [
            'user1' => $this->input('user1'),
            'user2' => $this->input('user2'),
        ];
    }
}
