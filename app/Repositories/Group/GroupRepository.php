<?php

namespace App\Repositories\Group;

use App\Models\Chat\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRepository
{
    public function __construct(public Group $model) {}

    public function index($search, $receiver = null)
    {
        $receiver = $receiver ?? auth()->user()->id;

        return $this->model->with([
            'message.creator:id,name,uuid',
            'message.attachment',
        ])
            ->withCount('unread_messages')
            ->whereHas('users', function ($q) use ($receiver) {
                $q->where('group_users.user_id', $receiver);
            })
            ->get();
    }

    public function store(array $groupData, array $userUuids): Group
    {
        $group = $this->model::create(
            $groupData
        );

        $group->users()->attach($userUuids);

        return $group->load('users:id,uuid,name');
    }

    public function update($data, $uuid)
    {
        $data = array_filter($data, fn ($value) => ! is_null($value));

        $this->model::where('uuid', $uuid)->update($data);

        return $this->model::where('uuid', $uuid)->first();
    }

    public function addUser($uuid, array $userUuids): Group
    {
        $group = $this->model::where('uuid', $uuid)->first();

        $group->users()->attach($userUuids);

        return $group->load('users:id,uuid,name');
    }

    public function show($uuid, $relations = []): Group|Model
    {
        $query = $this->model->query();

        if (count($relations)) {
            $query->with($relations);
        }

        return $query->where('uuid', $uuid)->first();
    }

    public function destroy($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }

    public function getUsers($uuid)
    {
        return $this->show($uuid, ['users:id,uuid,name,profile_picture']);
    }

    public function deleteUserFromGroup($data)
    {
        $uuid = $data['uuid'];
        $user_id = $data['user_id'];
        $group = $this->model->where('uuid', $uuid)->first();

        return $group->users()->detach($user_id);
    }
}
