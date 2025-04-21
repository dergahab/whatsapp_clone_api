<?php

namespace App\Repositories\Attachment;

use App\Models\Attachments;

class AttachmentRepository
{
    public function __construct(public Attachments $model) {}

    public function store($file)
    {
        dd($file);
        return $this->model->create($file);
    }

}
