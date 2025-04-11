<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Services\Group\GroupService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct(public GroupService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//	    try {
		    return rp_response($this->service->index(), __('ProcessSuccessfully'), Response::HTTP_CREATED);
//	    } catch (\Exception $ex) {
//		    return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
//	    }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->store($request);
            DB::commit();

            return rp_response($group, __('GroupCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @lrd:start
     *
     * group update api
     *
     * @lrd:end
     */
    public function update(UpdateRequest $request, string $uuid)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->update($request, $uuid);
            DB::commit();

            return rp_response($group, __('GroupUpdateSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addUser(AddUserToGroupRequest $request)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->addUser($request);
            DB::commit();

            return rp_response($group, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
