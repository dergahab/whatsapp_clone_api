<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ChatStoreRequest;
use App\Http\Requests\Chat\DestroyRequest;
use App\Http\Requests\Chat\SearcRequest;
use App\Http\Requests\Chat\ShowRequest;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function __construct(public ChatService $service) {}

    public function index(SearcRequest $request)
    {
        DB::beginTransaction();
        try {
            $chat = $this->service->index($request);

            DB::commit();

            return rp_response($chat);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ChatStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validatedData();
            $chat = $this->service->store($data);

            DB::commit();

            return rp_response($chat, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @lrd:start
     *
     * Attributes:
     *
     * - **create_by** (string): Specifies who created the resource.
     *   - Possible values:
     *     - **sender**: The creator is the sender.
     *     - **receiver**: The creator is the receiver.
     *
     * - **edit_status** (bool): Indicates if the resource has been edited.
     *   - Possible values:
     *     - **true**: The resource has been edited.
     *     - **false**: The resource has not been edited.
     *
     * - **status** (string): Represents the current status of the resource.
     *   - Possible values:
     *     - **sended**: The resource has been sent.
     *     - **accepted**: The resource has been accepted.
     *     - **readed**: The resource has been read.
     *
     * @lrd:end
     */
    public function show(ShowRequest $request)
    {
        DB::beginTransaction();
        try {

            $chat = $this->service->show($request);

            return rp_response(data: $chat, message: Response::HTTP_OK);

        } catch (\Exception $ex) {

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DestroyRequest $request)
    {
        DB::beginTransaction();
        try {
            $chat = $this->service->destroy($request);
            DB::commit();
            return rp_response($chat, __('DataDeletedSuccessfully'), Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
