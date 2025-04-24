<?php

namespace App\Http\Controllers\Vault;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Menage\DestroyRequest;
use App\Http\Requests\Menage\UpdateRequest;
use App\Services\Vault\MenageCredentialsService;
use App\Http\Requests\Menage\StoreRequest;
use App\Http\Requests\Menage\ShowRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MenageCredentialsController extends Controller
{
    public function __construct(private MenageCredentialsService $service) {}

    public function index()
    {
        try {
            $passwords = $this->service->index();
            return rp_response($passwords, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $passwords = $this->service->store($request);
            DB::commit();
            return rp_response($passwords, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $passwords = $this->service->update($request, $uuid);
            DB::commit();
            return rp_response($passwords, __('DataUpdatedSuccessfully'), Response::HTTP_OK);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(ShowRequest $request)
    {
        try {
            $password = $this->service->show($request);

            return rp_response( $password, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DestroyRequest $request)
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
