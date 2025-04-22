<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\DestroyRequest;
use App\Http\Requests\Message\ShowAllMessageRequest;
use App\Http\Requests\Message\ShowRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Services\Attachment\AttachmentService;
use App\Services\Message\MessageService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function __construct(public MessageService $service,public AttachmentService $attachmentService) {}

    public function store(StoreRequest $request)
    {
//        DB::beginTransaction();
//        try {
           $message=$this->service->store($request);
            if ($request->file('file')) {
                $this->attachmentService->store($request->file('file'),$message->id);
            }
            DB::commit();

            return rp_response([], __('DataCreatedSuccessfully'), Response::HTTP_CREATED);

//        } catch (\Exception $ex) {
//            DB::rollBack();
//
//            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
    }

    public function show(ShowRequest $request)
    {
        DB::beginTransaction();
        try {

            $message = $this->service->show($request);

            return rp_response(data: $message, message: Response::HTTP_OK);

        } catch (\Exception $ex) {

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, string $uuid)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $uuid);
            DB::commit();

            return rp_response($this->service->show(new ShowRequest($request->toArray())), __('DataCreatedSuccessfully'), Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DestroyRequest $request)
    {
        DB::beginTransaction();
        try {
            $message = $this->service->destroy($request);
            DB::commit();

            return rp_response($message, __('DataDeletedSuccessfully'), Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();

            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show_messages(ShowAllMessageRequest $request)
    {
        try {
            $messages = $this->service->showAllMessages($request);

            return rp_response(data: $messages, message: Response::HTTP_OK);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
