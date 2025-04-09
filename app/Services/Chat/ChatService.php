<?php

namespace App\Services\Chat;

use App\Http\Requests\Chat\DestroyRequest;
use App\Http\Requests\Chat\SearcRequest;
use App\Http\Requests\Chat\ShowRequest;
use App\Repositories\Chat\ChatRepository;
use Illuminate\Http\Request;


class ChatService
{
    public function __construct(public ChatRepository $repository) {}

    public function index(SearcRequest $request)
    {
        return $this->repository->index($request?->search);
    }
    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show(ShowRequest $request)
    {
        return $this->repository->show($request->uuid , $request->page);
    }

    public function destroy(DestroyRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }
}
