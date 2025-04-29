<?php

namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;
use App\Models\Attachments;

class AttachmentStoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            "attachment_id" =>["required","uuid", "exists:".rp_get_table(Attachments::class).",uuid"],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'attachment_id' => rp_uuid_to_id(Attachments::class, $this->input('attachment_id')),
        ]);
    }
}
