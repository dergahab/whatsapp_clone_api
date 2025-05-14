<?php

namespace App\Repositories\User;

use App\Models\Chat\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryİnterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new User;
    }

    public function index($data = []): array
    {
        $groupId = $data['uuid'] ?? null;
        $search = $data['search'] ?? null;

        $excludedUserIds = [];
        if ($groupId) {
            $group = Group::find($groupId);
            if ($group) {
                $excludedUserIds = $group->users()->pluck('users.id')->toArray();
            }
        }
        $excludedUserIds[] = auth()->id();


        return User::select('uuid', 'name', 'profile_picture', 'type')
            ->whereNotIn('id', $excludedUserIds)
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
        $data = array_filter($data, fn ($value) => ! is_null($value));

        User::where('uuid', $uuid)->update($data);

        return User::where('uuid', $uuid)->first();
    }

    public function show($uuid): ?User
    {
        return User::select('name', 'email', 'profile_picture')->where('uuid', $uuid)->first();
    }

    public function list($search = null): array
    {
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
            })
            ->select('uuid', 'name')->get()->toArray();
    }
}
