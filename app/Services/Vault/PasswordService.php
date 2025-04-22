<?php

namespace App\Services\Vault;

use App\Http\Requests\Vault\DestroyPasswordRequest;
use App\Http\Requests\Vault\StorePasswordRequest;
use App\Repositories\Vault\PasswordRepository;
use App\Http\Requests\Vault\UpdateRequest;
use App\Http\Requests\Vault\IndexRequest;
use App\Http\Requests\Vault\ShowRequest;
use Illuminate\Support\Facades\Crypt;

class PasswordService
{
    public function __construct(public PasswordRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        return $this->repository->index($request->search);
    }

    public function store(StorePasswordRequest $request)
    {
        // dd($request->validatedData());
        $password = $request->validatedData();
        return $this->repository->store($password);
    }

    public function show(ShowRequest $request)
    {
        $password = $this->repository->show($request->uuid);
        $password->credential = rescue(fn() => json_decode(Crypt::decrypt(optional($password)->credential)), null);
        
        return $password;
    }

    public function update(UpdateRequest $request, $uuid)
    {
        $data = $request->validatedData();

        return $this->repository->update($data, $uuid);
    }

    public function destroy(DestroyPasswordRequest $request)
    {
        return $this->repository->destroy($request->uuid);
    }
}
