<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryİnterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index($search = null, $group = null): array
    {
        return User::select('uuid', 'name', 'profile_picture', 'type')
            ->where('uuid', '!=', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
	        ->when($group, function ($query) use ($group) {
		        $query->whereHas('groups', function ($q) use ($group, $query) {
			        $q->where('group_id', "!=", $group);
		        });
	        })
	        ->where('id', '!=', auth()->user()->id)
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
        $data = array_filter($data, fn($value) => !is_null($value));

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
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->select('uuid', 'name')->get()->toArray();
    }
}
