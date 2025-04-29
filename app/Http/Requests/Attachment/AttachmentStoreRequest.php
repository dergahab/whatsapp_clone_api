<?php

namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;
use App\Models\Attachments;

class AttachmentStoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            "uuid" =>["required","uuid", "exists:".rp_get_table(Attachments::class).",uuid"],
        ];
    }
}
