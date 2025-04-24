<?php

namespace App\Http\Requests\Menage;

use App\Http\Requests\BaseRequest;
use App\Models\User;

class ShowRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'uuid' => 'required|exists:' . rp_get_table(User::class) . ',uuid',
        ];
    }
}
