<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(public UserRepository $repositories) {}

    public function index(Request $request)
    {
        return $this->repositories->index($request?->search);
    }
    public function store(array $data)
    {
        return $this->repositories->store($data);
    }
}
