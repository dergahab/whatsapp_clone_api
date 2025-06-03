<?php

namespace App\Repositories\Message;

use App\Models\Chat\Message;

class MessageRepository
{
    public function __construct(public Message $model) {}

    public function store(array $data): Message
    {
        return $this->model->create($data);
    }

    public function show($uuid): ?Message
    {
        return $this->model->where('uuid', $uuid)->with('creator', 'attachment')->first();
    }

    public function update(array $data, $uuid): Message
    {
        $message = $this->model->where('uuid', $uuid)->first();

        $message->fill($data);

        $message->save();

        return $message;
    }

    public function showAllMessages($chat_id, $page = 1): array
    {
        $this->changeMessageStatus($chat_id, 2);
        $messages = $this->model
            ->where('chat_id', $chat_id)
            ->with('creator', 'attachment')
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'page', $page);

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

    public function destroy($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }

    public function changeMessageStatus($chat_id, $status)
    {
        $this->model
            ->where('chat_id', $chat_id)
            ->update(['status' => $status]);
    }
}
