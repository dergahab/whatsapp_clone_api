<?php

namespace App\Http\Controllers\Vault;

use App\Http\Requests\Vault\DestroyPasswordRequest;
use App\Http\Requests\Vault\StorePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Vault\UpdateRequest;
use App\Http\Requests\Vault\IndexRequest;
use App\Http\Requests\Vault\ShowRequest;
use App\Services\Vault\PasswordService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class PasswordController extends Controller
{
    public function __construct(public PasswordService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        try {
            $passwords = $this->service->index($request);
            return rp_response($passwords, __('DataFetchedSuccessfully'), Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StorePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $password = $this->service->store($request);
            DB::commit();
            return rp_response($password, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(ShowRequest $request)
    {
        try {
            $password = $this->service->show($request);

            return rp_response(data: $password, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, string $uuid)
    {
        DB::beginTransaction();
        try {
            $password = $this->service->update($request, $uuid);
            DB::commit();

            return rp_response($password, __('PasswordUpdatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function destroy(DestroyPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = $this->service->destroy($request);
            DB::commit();
            return rp_response($result, __('DataDeletedSuccessfully'), Response::HTTP_OK);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
