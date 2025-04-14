<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryİnterface
{
    public function index($search = null): array
    {
        return User::select('uuid', 'name', 'profile_picture')
            ->where('uuid', '!=', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->get()
            ->toArray();
    }

    public function store(array $data): User
    {
        unset($data['password_confirmation']);

        return User::create($data);
    }

    public function update(array $data, string $uuid): ?User
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $user->update($data);

        return $user;
    }

    public function show($uuid): ?User
    {
        return User::select('name', 'email', 'profile_picture')->where('uuid', $uuid)->first();
    }
}
