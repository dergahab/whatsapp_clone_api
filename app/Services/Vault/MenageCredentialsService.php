<?php

namespace App\Services\Vault;

use App\Repositories\Vault\MenageCredentialsRepository;
use App\Http\Requests\Menage\DestroyRequest;
use App\Http\Requests\Menage\StoreRequest;
use App\Http\Requests\Menage\ShowRequest;
use App\Http\Requests\Menage\UpdateRequest;


class MenageCredentialsService
{
    protected $repository;

    public function __construct(MenageCredentialsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): array
    {
        return $this->repository->index()->toArray();
    }

    public function store(StoreRequest $service): array
    {
        return $this->repository->store($service->user_id, $service->credentialsData())->toArray();
    }

    public function update(UpdateRequest $service, $uuid): array
    {
        return $this->repository->update($service->credentialsData(), $uuid)->toArray();
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
