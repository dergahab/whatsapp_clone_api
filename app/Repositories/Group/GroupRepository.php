<?php

namespace App\Repositories\Group;

use App\Models\Chat\Group;

class GroupRepository
{
    public function __construct(public Group $model) {}

    public function index()
    {
        return $this->model->with([
            'message.creator:id,name,uuid',
        ])
            ->withCount('unread_messages')
            ->get();
    }

    public function store(array $groupData, array $userUuids): Group
    {
        $group = Group::create(
            $groupData
        );

        $group->users()->attach($userUuids);

        return $group->load('users:id,uuid,name');
    }

    public function update($data, $uuid)
    {
        $group = Group::where('uuid', $uuid)->firstOrFail();

        $group->update($data);

        return $group;
    }

    public function addUser($uuid, array $userUuids): Group
    {
        $group = $this->model::where('uuid', $uuid)->first();

        $group->users()->attach($userUuids);

        return $group->load('users:id,uuid,name');
    }

    public function show($uuid): ?Group
    {
        return Group::select('name', 'file')->where('uuid', $uuid)->first();
    }
}
