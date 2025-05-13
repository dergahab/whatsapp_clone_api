<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\DeleteUserFromGroupRequest;
use App\Http\Requests\Group\DestroyRequest;
use App\Http\Requests\Group\ShowRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Services\Group\GroupService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct(public GroupService $service) {}


    /**
     * Store a newly created resource in storage.
     */
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
    public function show(ShowRequest $request)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->show($request);

            return rp_response(data: $group, message: Response::HTTP_OK);

        } catch (\Exception $ex) {

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
    public function destroy(DestroyRequest $request)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->destroy($request);
            DB::commit();

            return rp_response($group, __('DataDeletedSuccessfully'), Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    public function getUsers(ShowRequest $request)
    {
        try {
            $data = $this->service->getUsers($request);
            return rp_response(data: $data, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUserFromGroup(DeleteUserFromGroupRequest $request)
    {
        DB::beginTransaction();
        try {
            $data=$request->validationData();
            $this->service->deleteUserFromGroup($data);
            DB::commit();

            return rp_response($data, __('UserDeletedSuccessfully'), Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
