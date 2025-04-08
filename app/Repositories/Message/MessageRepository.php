<?php

namespace App\Repositories\Message;

use App\Models\Chat\Message;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

class MessageRepository
{
    public function __construct(public Message $model) {}

    public function store(array $data): Message
    {
        return $this->model->create($data);
    }
    public function show($uuid): ?Message
    {
        return $this->model->where('uuid', $uuid)->with('creator')->first();
    }
    public function update(array $data,$uuid): int
    {
        return $this->model->where('uuid',$uuid)->update($data);
    }
    public function showAllMessages($chat_id, $page = 1): array
    {
        $messages =  $this->model
            ->where('chat_id', $chat_id)
            ->select('uuid', 'message')
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'page', $page);

        return [
            'current_page' => $messages->currentPage(),
            'data' => $messages->items(),
            'from' => $messages->firstItem(),
            'last_page' => $messages->lastPage(),
            'per_page' => $messages->perPage(),
            'to' => $messages->lastItem(),
            'total' => $messages->total()
        ];
    }

}
