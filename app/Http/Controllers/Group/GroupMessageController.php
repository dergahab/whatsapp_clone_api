<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        //
    }


    public function store(StoreRequest $request)
    {

	    DB::beginTransaction();
        try {

	    $group = $this->service->store($request);
	    DB::commit();
	    return rp_response($group, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'),  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
