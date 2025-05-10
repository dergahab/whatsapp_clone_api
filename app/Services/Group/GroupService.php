<?php

namespace App\Services\Group;

use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\DestroyRequest;
use App\Http\Requests\Group\ShowRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Http\Resources\GroupResource;
use App\Http\Requests\Group\UpdateRequest;
use App\Models\Chat\Group;
use App\Repositories\Group\GroupRepository;
use App\Services\Base;


class GroupService extends Base
{
    public function __construct(public GroupRepository $repository) {}

	public function index(SearchRequest $request, $receiver = null)
	{
		 $data = $this->repository->index($request->search, $receiver);

		return GroupResource::collection($data)->resolve();
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
        $filepath = $filenames[0];
        if ($request->hasFile('file') && $filenames->isNotEmpty()) {
            $this->fileDeleteStorage($filepath);
            $groupdata['file'] = $this->fileUploadStorage($request->file('file'), 'group');
        }

        return $this->repository->update($groupdata, $uuid);
    }

    public function addUser(AddUserToGroupRequest $request): Group
    {
        return $this->repository->addUser($request->group_uuid, $request->groupUsersData());
    }

    public function show(ShowRequest $request)
    {
        return $this->repository->show($request->uuid, $request->page);
    }

    public function destroy(DestroyRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }
}
