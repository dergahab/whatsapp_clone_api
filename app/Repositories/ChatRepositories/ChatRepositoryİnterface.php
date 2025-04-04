<?php

namespace App\Repositories\ChatRepositories;

use App\Models\Chat\Chat;

interface ChatRepositoryİnterface
{
    public function store(array $data): Chat;
}
