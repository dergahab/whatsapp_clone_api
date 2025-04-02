<?php
namespace App\Services\Chat;
use App\Repositories\ChatRepositories\ChatRepository;
use Illuminate\Http\Request;

class ChatService
{
    public function __construct(public ChatRepository $repositories)
    {

    }


    public function store(array $data)
    {
        return $this->repositories->store($data);
    }

    public function index(Request $request)
    {
        return $this->repositories->index($request?->search);
    }

}
