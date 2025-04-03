<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Services\Message\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
	public function __construct( public MessageService $service)
	{

	}


    public function store(StoreRequest $request)
    {
//	    DB::beginTransaction();
//	    try {
		    $this->service->store($request);

		    return  rp_response([], __('DataCreatedSuccessfully'),Response::HTTP_CREATED);

//	    } catch (\Exception $ex) {
//
//		    return  rp_response([], __('FailureProcess'),Response::HTTP_INTERNAL_SERVER_ERROR);
//	    }
    }

	public function show(string $id)
	{
		//
	}

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
