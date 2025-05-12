<?php

namespace App\Repositories\Chat;

use App\Models\Chat\Chat;
use App\Services\Message\MessageService;

class ChatRepository implements ChatRepositoryİnterface
{
    public function __construct(public Chat $model, public MessageService $messageService) {}

    public function index($search, $receiver)
    {
			$userId = $receiver ?? auth()->user()->id;
           return $this->model->with([
	           'message.creator:id,name,uuid',
               'message.attachment',
               'receiver'
           ])
           ->where(function ($query) use ($userId) {
	           $query->where('user1', $userId)
		           ->orWhere('user2', $userId);
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

    public function getReceiver($uuid)
    {
        $result = $this->model->where(['uuid' => $uuid])->with('receiver')->first();
        return [$result?->receiver->uuid ?? null];
    }
}
