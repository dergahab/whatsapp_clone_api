<?php

namespace App\Repositories\Chat;

use App\Models\Chat\Chat;

class ChatRepository implements ChatRepositoryİnterface
{
    public function __construct(public Chat $model) {}

    public function index($search = null): array
    {
        return $this->model
            ->with([
                'message',
                'sendBy:id,uuid,name,profile_picture'
            ])
            ->withCount(['message as unread_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('sendBy', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->get()
            ->toArray();
    }

    public function store(array $data): Chat
    {
        if ($data['user1'] === $data['user2']) {
            throw new \InvalidArgumentException('User1 and User2 cannot be the same.');
        }
        $existingChat = Chat::where(function ($query) use ($data) {
            $query->where('user1', $data['user1'])
                ->where('user2', $data['user2']);
        })
            ->orWhere(function ($query) use ($data) {
                $query->where('user1', $data['user2'])
                    ->where('user2', $data['user1']);
            })
            ->exists();

        if ($existingChat) {
            throw new \InvalidArgumentException('A chat between these users already exists.');
        }

        return Chat::create($data);
    }

    public function show($uuid): ?Chat
    {
        return $this->model->where('uuid', $uuid)->with(['message','sendBy'])->first();
    }

    public function destroy($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }
}
