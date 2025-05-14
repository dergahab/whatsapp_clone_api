<?php

namespace App\Repositories\Vault;

use App\Models\Vault\Password;

class CredentialsRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Password;
    }

    public function index($search = null, $page = 1)
    {
        $credentials = $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->when(auth()->user()->type === 'user', function ($query) {
                $query->whereHas('users', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                });
            })
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

    public function list($search = null)
    {
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%');
                });
            })
            ->select('uuid', 'title as name')
            ->get();
    }
}
