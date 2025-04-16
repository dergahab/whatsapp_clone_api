<?php

	namespace App\Services\Group;

	use App\Events\GroupNewMessageSendedEvent;
    use App\Http\Requests\GroupMessage\ShowAllMessageRequest;
    use App\Http\Requests\GroupMessage\StoreRequest;
    use App\Models\Chat\Group;
    use App\Models\Chat\Message;
    use App\Repositories\Group\GroupMessageRepository;

	class GroupMessageService
	{
		public function __construct(public GroupMessageRepository $repository) {}
        public function store(StoreRequest $request): Message
        {
            $message = $this->repository->store($request->validatedData());
            $data = $this->repository->show($message->uuid)->toArray();
            event(new GroupNewMessageSendedEvent($data, rp_id_to_uuid(Group::class, $request->group_id)));
            return $message;
        }

        public function showAllMessages(ShowAllMessageRequest $request)
        {
            return $this->repository->showAllMessages($request->group_id, $request->input('page', 1));
        }
	}
