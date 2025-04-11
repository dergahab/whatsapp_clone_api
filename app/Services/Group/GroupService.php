<?php

namespace App\Services\Group;

use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Resources\GroupResource;
use App\Http\Requests\Group\UpdateRequest;
use App\Models\Chat\Group;
use App\Repositories\Group\GroupRepository;
use App\Services\Base;

class GroupService extends Base
{
    public function __construct(public GroupRepository $repository) {}

	public function index()
	{
		 $data = $this->repository->index();

		return GroupResource::collection($data);
	}

	public function store(StoreRequest $request): Group
    {
        $groupdata = $request->groupData();
        if ($request->file('file')) {
            $groupdata['file'] = $this->fileUploadStorage($request->file('file'), 'group');
        }

        return $this->repository->store(
            $groupdata,
            $request->groupUsersData()
        );
    }

    public function update(UpdateRequest $request, $uuid)
    {
        $filenames = Group::where('uuid', $request->uuid)->pluck('file');
        $groupdata = $request->validated();

        if ($request->hasFile('file') && $filenames->isNotEmpty()) {
            $this->fileDeleteStorage($filenames);
            $groupdata['file'] = $this->fileUploadStorage($request->file('file'), 'group');
        }

        return $this->repository->update($groupdata, $uuid);
    }



    public function addUser(AddUserToGroupRequest $request): Group
    {
        return $this->repository->addUser($request->group_uuid, $request->groupUsersData());
    }
}
