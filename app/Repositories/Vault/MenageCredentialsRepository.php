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
        return $this->model->with([ "passwords:uuid,title" ])->get();
    }

    public function store($uuid, $credentials): User
    {
         $this->getByUuid($uuid)->passwords()->attach($credentials);

        return $this->show($uuid);
    }

    public function update($credentials, $uuid): User
    {

        $this->getByUuid($uuid)->passwords()->sync($credentials);

        return $this->show($uuid);
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
