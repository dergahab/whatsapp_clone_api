<?php

namespace App\Services\Group;


use App\Models\Chat\Group;
use App\Repositories\Group\GroupRepository;

class GroupService
{
    public function __construct(public GroupRepository $repository) {}

    public function store($groupData, $userUuids): Group
    {
        return $this->repository->store(
            $groupData,
            $userUuids
        );
    }
}
