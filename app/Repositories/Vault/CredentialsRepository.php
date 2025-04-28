<?php

namespace App\Repositories\Vault;

use App\Models\Vault\Password;

class CredentialsRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Password();
    }

    public function index($search = null)
    {
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when(auth()->user()->type === 0, function ($query) {
                $query->whereHas('users', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(array $data): Password
    {
        return $this->model->create($data);
    }

    public function show($uuid): ?Password
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function update(array $data, $uuid)
    {
        $this->model->where('uuid', $uuid)->update($data);

        return $this->show($uuid);
    }

    public function destroy(string $uuid): bool
    {
        return Password::where('uuid', $uuid)->delete();
    }
}
