<?php

namespace App\Repositories\Vault;

use App\Models\Vault\UserPassword;
use App\Models\User;

class MenageCredentialsRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index($page = 1)
    {
        $credentials =$this->model
            ->whereHas('passwords')
            ->with([ "passwords:uuid,title" ])
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'page', $page);

            return [
                'current_page' => $credentials->currentPage(),
                'data' => $credentials->items(),
                'from' => $credentials->firstItem(),
                'last_page' => $credentials->lastPage(),
                'per_page' => $credentials->perPage(),
                'to' => $credentials->lastItem(),
                'total' => $credentials->total(),
            ];
    }

    public function store( $request): User
    {
        $this->getByUuid($request->user_id)->passwords()->attach($request->credentials);

        return $this->show($request->user_id);
    }

    public function update($request): User
    {
        $this->getByUuid($request->user_id)->passwords()->sync($request->credentials);

        return $this->show($request->user_id);
    }

    public function show(string $uuid): User
    {
        return $this->model->where('uuid', $uuid)->with([ "passwords:uuid,title" ])->first();
    }

    public function destroy(string $uuid): bool
    {
        return UserPassword::where('uuid', $uuid)->delete();
    }

    public function getByUuid(string $uuid): User
    {
        return $this->model->where('uuid', $uuid)->first();
    }
}
