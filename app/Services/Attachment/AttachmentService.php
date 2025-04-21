<?php

namespace App\Services\Attachment;


use App\Http\Requests\Message\StoreRequest;
use App\Repositories\Attachment\AttachmentRepository;

class AttachmentService
{
    public function __construct(public AttachmentRepository $repository) {}

    public function store($file)
    {
        $this->repository->store($file);
    }

}
