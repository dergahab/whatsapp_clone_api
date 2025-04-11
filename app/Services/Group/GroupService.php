<?php

namespace App\Services\Group;

use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Models\Chat\Group;
use App\Repositories\Group\GroupRepository;
use App\Services\Base;

class GroupService extends Base
{
    public function __construct(public GroupRepository $repository) {}

	public function index()
	{
		return collect($this->repository->index())->map(function ($item) {
			if ($item->message) {
				$item->message->create_by_label = $item->message?->createByLabel;
			}
			return $item;
		});
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

    public function addUser(AddUserToGroupRequest $request): Group
    {
        return $this->repository->addUser($request->group_uuid, $request->groupUsersData());
    }
}
