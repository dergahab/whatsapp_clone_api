<?php

namespace App\Repositories\ChatRepositories;

use App\Models\Chat\Chat;

class ChatRepository implements ChatRepositoryİnterface
{
    public function __construct(public Chat $model) {}

    public function index()
    {
        return $this->model
            ->with([
                'message',
                'sendBy:id,uuid,name,profile_picture'
            ])
            ->withCount(['message as unread_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->get();
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
