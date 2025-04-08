<?php

namespace App\Repositories\Chat;

use App\Models\Chat\Chat;

interface ChatRepositoryİnterface
{
    public function store(array $data): Chat;
}
