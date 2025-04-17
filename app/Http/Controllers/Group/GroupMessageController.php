<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMessage\ShowAllMessageRequest;
use App\Http\Requests\GroupMessage\StoreRequest;
use App\Services\Group\GroupMessageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GroupMessageController extends Controller
{
	public function __construct(public GroupMessageService $service)
	{

	}
    public function store(StoreRequest $request)
    {
	    DB::beginTransaction();
        try {
        $data=$request->validationData();
	    $group = $this->service->store($data);
	    DB::commit();
	    return rp_response($group, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'),  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show_messages(ShowAllMessageRequest $request)
    {
        try {
            $messages = $this->service->showAllMessages($request);

            return rp_response(data: $messages, message: \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
