<?php

namespace App\Http\Requests;

use App\Models\Groups;
use Illuminate\Foundation\Http\FormRequest;

class GroupCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function createGroup()
    {
        return Groups::create([
            'name' => $this->input('name'),
        ]);
    }
}
