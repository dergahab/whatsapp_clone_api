<?php

namespace App\Services\Message;

use App\Events\NotificationEvent;
use App\Events\ChatNewMessageSendedEvent;
use App\Http\Requests\Message\DestroyRequest;
use App\Http\Requests\Message\ShowAllMessageRequest;
use App\Http\Requests\Message\ShowRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Repositories\Message\MessageRepository;
use App\Services\Attachment\AttachmentService;
use Illuminate\Http\Request;

class MessageService
{
    public function __construct(public MessageRepository $repository,public AttachmentService $attachmentService) {}

    public function index(Request $request)
    {
        return $this->repository->index($request?->search);
    }

    public function store(StoreRequest $request): Message
    {
        $data = $request->validationData();
        $message = $this->repository->store($data);

        if ($request->file('file')) {
            $this->attachmentService->store($request->file('file'), $message->id);
        }
        return $message;
    }

    public function show(ShowRequest $request)
    {
        return $this->repository->show($request->uuid);
    }

    public function update(UpdateRequest $request, $uuid): int
    {
        return $this->repository->update($request->validatedData(), $uuid);
    }

    public function showAllMessages(ShowAllMessageRequest $request)
    {
        return $this->repository->showAllMessages($request->chat_id, $request->input('page', 1));
    }

    public function destroy(DestroyRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }


}
