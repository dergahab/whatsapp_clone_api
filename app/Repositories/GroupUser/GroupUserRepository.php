<?php

namespace App\Repositories\GroupUser;

use App\Models\Chat\GroupUser;

class GroupUserRepository
{
    public function __construct(public GroupUser $model){}

    public function index()
    {
        dd(1111);
       return 11111;
    }


}
