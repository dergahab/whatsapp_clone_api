<?php

namespace App\Repositories\ChatRepositories;


use App\Models\Chat\Chat;
use Illuminate\Support\Facades\Auth;

class ChatRepository implements ChatRepositoryİnterface
{


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
}
