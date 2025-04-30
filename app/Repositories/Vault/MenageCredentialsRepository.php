<?php

namespace App\Repositories\Vault;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Vault\UserPassword;
use App\Models\User;

class MenageCredentialsRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index(): Collection
    {
        return $this->model->whereHas('passwords')->with([ "passwords:uuid,title" ])->get();
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
