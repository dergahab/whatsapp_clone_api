<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\DestroyRequest;
use App\Http\Requests\Chat\ShowRequest;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Chat\ChatStoreRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class ChatController extends Controller
{
    public function __construct(public ChatService $service)
    {
    }

    public function index(Request $request)
    {
	    DB::beginTransaction();
	    try {
		    $chat = $this->service->index($request);

		    DB::commit();
		    return  rp_response($chat);

	    } catch (\Exception $ex) {
		    DB::rollBack();

		    return  rp_response([], __('FailureProcess'),Response::HTTP_INTERNAL_SERVER_ERROR);
	    }
    }

    public function store(ChatStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validatedData();
            $chat = $this->service->store($data);

            DB::commit();
			return  rp_response($chat, __('DataCreatedSuccessfully'),Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();

	        return  rp_response([], __('FailureProcess'),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show(ShowRequest $request)
    {
//	    DB::beginTransaction();
//	    try {

		    $chat = $this->service->show($request);

		    return  rp_response(data:$chat,message: Response::HTTP_OK);

//	    } catch (\Exception $ex) {
//
//		    return  rp_response([], __('FailureProcess'),Response::HTTP_INTERNAL_SERVER_ERROR);
//	    }
    }

    public function destroy(DestroyRequest $request)
    {
	    DB::beginTransaction();
	    try {
		    $chat = $this->service->destroy($request);

		    return  rp_response($chat, __('DataDeletedSuccessfully'),Response::HTTP_OK);

	    } catch (\Exception $ex) {

		    return  rp_response([], __('FailureProcess'),Response::HTTP_INTERNAL_SERVER_ERROR);
	    }
    }
}
