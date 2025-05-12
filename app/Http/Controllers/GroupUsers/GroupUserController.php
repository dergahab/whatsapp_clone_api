<?php

namespace App\Http\Controllers\GroupUsers;

use App\Http\Controllers\Controller;
use App\Services\GroupUser\GroupUserService;
use Illuminate\Http\Response;
use App\Http\Requests\GroupUser\IndexRequest;
class GroupUserController extends Controller
{
    public function __construct(public GroupUserService $service) {}

    public function index(IndexRequest $request)
    {
        try {
            $data=$request->validationData();
            dd($data);
            $data = $this->service->index();
            return rp_response(data: $data, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
