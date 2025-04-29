<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\AttachmentStoreRequest;
use App\Services\Attachment\AttachmentService;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    public function __construct(public AttachmentService $service) {}

    public function fileDownload(AttachmentStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            dd($data);
            $response = $this->service->fileDownload($data);
            return $response;
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
