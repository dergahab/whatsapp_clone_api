<?php

namespace App\Services\Chat;

use App\Http\Requests\Chat\DestroyRequest;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Http\Requests\Chat\ShowRequest;
use App\Http\Resources\ChatResource;
use App\Repositories\Chat\ChatRepository;
use App\Repositories\Message\MessageRepository;
use Symfony\Component\HttpFoundation\Response;

class ChatService
{
    public function __construct(public ChatRepository $repository, public MessageRepository $messageRepository ,) {
    }
    public function index(SearchRequest $request, $receiver = null)
    {
	    $chats = $this->repository->index($request->search, $receiver);
	    return ChatResource::collection($chats)->resolve();
    }
    public function store(array $data)
    {
        if ($this->repository->findChat($data)) {
            $data = $this->messageRepository->showAllMessages($this->repository->findChat($data));
            return rp_response(data: $data, status: Response::HTTP_CREATED);
        }
        $data = $this->repository->store($data);
        return rp_response($data, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
    }
    public function show(ShowRequest $request)
    {
        return $this->repository->show($request->uuid, $request->page);
    }
    public function destroy(DestroyRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }
    public function getReceiver($uuid)
    {
        return $this->repository->getReceiver($uuid);
    }

}
