<?php

namespace App\Http\Requests\Groups;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class GroupListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Burada icazə qaydalarını təyin edə bilərsən
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Buraya request üçün doğrulama qaydalarını yaz
        ];
    }

    /**
     * Retrieve users data.
     */
    public function getUsersData(): array
    {
        return [
            'users' => User::select('uuid', 'name', 'email', 'profile_picture')->get(),
            'count' => User::count(),
        ];
    }
}
