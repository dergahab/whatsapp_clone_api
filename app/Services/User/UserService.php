<?php
namespace App\Services\User;
use App\Repositories\UserRepositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(public UserRepository $repositories)
    {

    }

    public function index(Request $request)
    {
        return $this->repositories->index($request?->search);
    }
}
