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

    public function updateUserProfile($uuid, $request)
    {
        $user = User::where('uuid', $uuid)->first();
        $data = [
            'name' => $request->has('name') ? $request->name : null,
            'email' => $request->has('email') ? $request->email : null,
            'profile_picture' => $request->has('profile_picture') ? $request->profile_picture : null
        ];
        if ($data['name'] !== null) {
            $user->name = $data['name'];
        }
        if ($data['email'] !== null) {
            $user->email = $data['email'];
        }
        if ($data['profile_picture'] !== null) {
            $user->profile_picture = $data['profile_picture'];
        }
        $user->save();
        return $user;
    }

}
