<?php

namespace App\Repositories\Chat;

use App\Models\Chat\Chat;
use App\Services\Message\MessageService;

class ChatRepository implements ChatRepositoryİnterface
{
    public function __construct(public Chat $model, public MessageService $messageService) {}

    public function index($search = null)
    {
        return $this->model
            ->with([
                'message',
                'sendBy:id,uuid,name,profile_picture',
            ])
            ->withCount(['message as unread_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('sendBy', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
            })
            ->get();
    }

    public function store(array $data): Chat
    {
        if ($data['user1'] === $data['user2']) {
            throw new \InvalidArgumentException('User1 and User2 cannot be the same.');
        }

        return Chat::create($data);
    }

    public function show($uuid): ?Chat
    {
        return $this->model->where('uuid', $uuid)->with(['message', 'sendBy'])->first();
    }

    public function destroy($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }

    public function findChat(array $data)
    {
        $chat = $this->model::where(function ($query) use ($data) {
            $query->where('user1', $data['user1'])
                ->where('user2', $data['user2']);
        })
            ->orWhere(function ($query) use ($data) {
                $query->where('user1', $data['user2'])
                    ->where('user2', $data['user1']);
            })
            ->first();

        return $chat?->id ?: false;
    }
}
