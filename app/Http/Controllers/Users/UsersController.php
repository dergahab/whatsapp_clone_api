<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\ListRequest;
use Spatie\LaravelIgnition\FlareMiddleware\AddQueries;

class UsersController extends Controller
{
    public function __construct(public UserService $service) {}

    /**
     * @LRDparam search nullable|string
     */
    public function index(IndexRequest $request)
    {
        try {
           $data = $this->service->index($request);
            return rp_response(data: $data, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, string $uuid)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $uuid);
            DB::commit();

            return rp_response([], message: __('UserProfileUpdatedSuccessfully'), status: Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $chat = $this->service->store($request);

            DB::commit();

            return rp_response($chat, __('DataCreatedSuccessfully'), \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(ShowRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->service->show($request);

            return rp_response(data: $user, message: Response::HTTP_OK);

        } catch (\Exception $ex) {

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function list(ListRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->service->list($request);

            return rp_response(data: $user, message: Response::HTTP_OK);

        } catch (\Exception $ex) {

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
