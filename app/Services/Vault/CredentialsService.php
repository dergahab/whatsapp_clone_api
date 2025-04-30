<?php

namespace App\Services\Vault;

use App\Http\Requests\User\ListRequest;
use App\Repositories\Vault\CredentialsRepository;
use App\Http\Requests\Vault\DestroyRequest;
use App\Http\Requests\Vault\UpdateRequest;
use App\Http\Requests\Vault\IndexRequest;
use App\Http\Requests\Vault\StoreRequest;
use App\Http\Requests\Vault\ShowRequest;
use Illuminate\Support\Facades\Crypt;

class CredentialsService
{
    public function __construct(public CredentialsRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        return $this->repository->index($request->search);
    }

    public function store(StoreRequest $request)
    {
        $password = $request->validatedData();
        return $this->repository->store($password);
    }

    public function show(ShowRequest $request)
    {
        $password = $this->repository->show($request->uuid);
        // $password->credential = rescue(fn() => json_decode(Crypt::decrypt(optional($password)->credential)), null);
        $password->credential = rescue(fn() => Crypt::decrypt(optional($password)->credential), null);
        return $password;
    }

    public function update(UpdateRequest $request, $uuid)
    {
        $data = $request->validatedData();
        return $this->repository->update($data, $uuid);
    }

    public function destroy(DestroyRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }

    public function list(ListRequest $request)
    {
        return $this->repository->list($request->search);
    }
}
