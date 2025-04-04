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
}
