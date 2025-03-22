<?php

namespace App\Http\Requests\Groups;

use App\Models\Groups;
use Illuminate\Foundation\Http\FormRequest;

class GroupDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:groups,id',
        ];
    }

    public function deleteGroup()
    {
        $group = Groups::findOrFail($this->input('name'));
        $group->delete();
    }
}
