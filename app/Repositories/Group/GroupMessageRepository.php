<?php

namespace App\Repositories\Group;
use App\Models\Chat\Group;
use App\Models\Chat\GroupUser;
use App\Models\Chat\Message;

class GroupMessageRepository
{
    public function __construct(public Message $model) {}
    public function store(array $data): Message
    {
       return $this->model::create($data);
    }
    public function showAllMessages($group_id, $page = 1): array
    {
        $messages = $this->model
            ->where('group_id', $group_id)
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
            'total' => $messages->total(),
        ];
    }
}
