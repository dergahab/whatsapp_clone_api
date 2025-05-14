<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\AttachmentStoreRequest;
use App\Services\Attachment\AttachmentService;
use Illuminate\Http\Response;

class AttachmentController extends Controller
{
    public function __construct(public AttachmentService $service) {}

    public function fileDownload(AttachmentStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $uuid = $validated['uuid'];
            $response = $this->service->fileDownload($uuid);

            if (! $response || ! file_exists($response)) {
                return rp_response([], __('File not found'), Response::HTTP_NOT_FOUND);
            }

            return response()->download($response, basename($response)); // Optional: dynamic file name
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
