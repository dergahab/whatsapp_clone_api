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

}
