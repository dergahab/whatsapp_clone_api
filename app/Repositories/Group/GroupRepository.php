<?php

namespace App\Repositories\Group;

use App\Models\Chat\Group;

class GroupRepository
{
    public function __construct(public Group $model) {}

    public function index($search)
    {
        return $this->model->with([
            'message.creator:id,name,uuid',
            'message.attachment'
        ])
//            ->when($search, function($q) use($search){
//                $q->where('name', 'like', '%'.$search.'%');
//            })
            ->withCount('unread_messages')
	        ->whereHas('users', function ($q) {
		        $q->where('group_users.user_id', auth()->user()->id);
	        })
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
        $data = array_filter($data, fn($value) => !is_null($value));

        Group::where('uuid', $uuid)->update($data);

        return Group::where('uuid', $uuid)->first();
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

    public function destroy($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }

}
