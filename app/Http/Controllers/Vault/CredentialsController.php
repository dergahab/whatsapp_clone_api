<?php

namespace App\Http\Controllers\Vault;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Vault\DestroyRequest;
use App\Services\Vault\CredentialsService;
use App\Http\Requests\Vault\UpdateRequest;
use App\Http\Requests\Vault\IndexRequest;
use App\Http\Requests\Vault\StoreRequest;
use App\Http\Requests\Vault\ShowRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\User\ListRequest;

class CredentialsController extends Controller
{
    public function __construct(public CredentialsService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        try {
            $this->authorize('index', User::class);
            $passwords = $this->service->index($request);
            return rp_response($passwords, __('DataFetchedSuccessfully'), Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $password = $this->service->store($request);
            $this->authorize('store', User::class);
            DB::commit();
            return rp_response($password, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(ShowRequest $request)
    {
        try {
            $this->authorize('show', User::class);
            $password = $this->service->show($request);
            return rp_response(data: $password, message: Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, string $uuid)
    {
        DB::beginTransaction();
        try {
            $this->authorize('update', User::class);
            $password = $this->service->update($request, $uuid);
            DB::commit();
            return rp_response($password, __('PasswordUpdatedSuccessfully'), Response::HTTP_CREATED);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DestroyRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = $this->service->destroy($request);
            $this->authorize('destroy', User::class);
            DB::commit();
            return rp_response($result, __('DataDeletedSuccessfully'), Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function list(ListRequest $request)
    {
        try {
            $passwords = $this->service->list($request);
            $this->authorize('list', User::class);
            return rp_response($passwords, __('DataFetchedSuccessfully'), Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return rp_response([], __('NotAcess'), Response::HTTP_FORBIDDEN);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
