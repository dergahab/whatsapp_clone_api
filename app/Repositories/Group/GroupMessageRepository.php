<?php

namespace App\Repositories\Group;

use App\Models\Chat\Message;
use App\Models\Read\MessageRead;

class GroupMessageRepository
{
    public function __construct(public Message $model, public MessageRead $messageRead)
    {
    }

    public function store(array $data): Message
    {
        return $this->model::create($data);
    }

    public function show($uuid): ?Message
    {
        return $this->model->where('uuid', $uuid)->with(['creator', 'attachment'])->first();
    }

    public function showAllMessages($group_id, $page = 1): array
    {
        $userId = auth()->user()->id;
        $this->changeMessageStatus($group_id, 2);

        $messages = $this->model
            ->where('group_id', $group_id)
            ->with('creator', 'attachment')
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'page', $page);

        $this->unread_messages($messages->pluck('id'), $userId);

        return [
            'current_page' => $messages->currentPage(),
            'data' => $messages->items(),
            'from' => $messages->firstItem(),
            'last_page' => $messages->lastPage(),
            'per_page' => $messages->perPage(),
            'to' => $messages->lastItem(),
            'total' => $messages->total(),
        ];
    }

    public function unread_messages($messageIds, int $userId): void
    {
        $this->messageRead::insertOrIgnore(
            collect($messageIds)
                ->filter(fn($id) => !$this->messageRead::where('message_id', $id)->where('user_id', $userId)->exists())
                ->map(fn($id) => ['message_id' => $id, 'user_id' => $userId, 'read_at' => now()])
                ->all()
        );
    }

    public function changeMessageStatus($group_id, $status)
    {
        $this->unread_messages($this->model->where('group_id', $group_id)->pluck('id'), auth()->user()->id);
        return
        $this->model
            ->where('group_id', $group_id)
            ->update(['status' => $status]);
    }
}
