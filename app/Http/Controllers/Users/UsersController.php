<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct(public UserService $service) {}

    /**
     * @LRDparam search nullable|string
     */
    public function index(Request $request)
    {
        try {
            return response()->json([
                'data' => $this->service->index($request),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, string $uuid)
    {
//        DB::beginTransaction();
//        try {
            $this->service->update($request, $uuid);
//            DB::commit();
//
//            return rp_response([], message: __('UserProfileUpdatedSuccessfully'), status: Response::HTTP_OK);
//
//        } catch (\Exception $ex) {
//            DB::rollBack();
//
//            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
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
}
