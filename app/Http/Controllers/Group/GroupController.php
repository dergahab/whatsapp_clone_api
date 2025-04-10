<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\AddUserToGroupRequest;
use App\Http\Requests\Group\StoreRequest;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
            return rp_response([], __('FailureProcess'),  Response::HTTP_INTERNAL_SERVER_ERROR);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function addUser(AddUserToGroupRequest $request)
    {
        DB::beginTransaction();
        try {
            $group = $this->service->addUser($request);
            DB::commit();
            return rp_response($group, __('DataCreatedSuccessfully'), Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'),  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
