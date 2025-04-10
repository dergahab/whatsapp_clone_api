<?php

namespace App\Repositories\Group;
use App\Models\Chat\Group;
use App\Models\Chat\GroupUser;

class GroupRepository
{
    public function __construct(public Group $model) {}

    public function store(array $groupData, array $userUuids): Group
    {
        $group = Group::create(
            $groupData
        );

        $group->users()->attach($userUuids);
        return $group->load('users:id,uuid,name');
    }

}
