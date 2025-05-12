<?php

namespace App\Services\GroupUser;
use App\Repositories\GroupUser\GroupUserRepository;
use App\Services\Base;
class GroupUserService extends Base
{
    public function __construct(public GroupUserRepository $repository){}

    public function index()
    {
        return $this->repository->index();
    }
}
