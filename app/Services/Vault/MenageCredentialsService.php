<?php

namespace App\Services\Vault;

use App\Http\Requests\Menage\DestroyRequest;
use App\Http\Requests\Menage\IndexRequest;
use App\Http\Requests\Menage\ShowRequest;
use App\Http\Requests\Menage\StoreRequest;
use App\Http\Requests\Menage\UpdateRequest;
use App\Repositories\Vault\MenageCredentialsRepository;

class MenageCredentialsService
{
    protected $repository;

    public function __construct(MenageCredentialsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(IndexRequest $request)
    {
        return $this->repository->index($request->input('page', 1));
    }

    public function store(StoreRequest $request): array
    {
        return $this->repository->store($request)->toArray();
    }

    public function update(UpdateRequest $request, $uuid): array
    {
        return $this->repository->update($request, $uuid)->toArray();
    }

    public function show(ShowRequest $request): array
    {
        return $this->repository->show($request->uuid)->toArray();
    }

    public function destroy(DestroyRequest $service): bool
    {
        return $this->repository->destroy($service['uuid']);
    }
}
