<?php

	namespace App\Services\Group;

	use App\Http\Requests\GroupMessage\StoreRequest;
	use App\Repositories\Group\GroupMessageRepository;

	class GroupMessageService
	{
		public function __construct(public GroupMessageRepository $repository) {}
		public function store(StoreRequest $request)
		{
			return $this->repository->store($request->validated());
		}
	}