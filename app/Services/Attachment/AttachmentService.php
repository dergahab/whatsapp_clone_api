<?php

namespace App\Services\Attachment;

use App\Repositories\Attachment\AttachmentRepository;
use App\Services\Base;

class AttachmentService extends Base
{
    public function __construct(public AttachmentRepository $repository) {}

    public function store($file,$message_id)
    {
        $fileInfo=$this->getFileInfo($file);
        $fileInfo['message_id'] = $message_id;
        $this->repository->store($fileInfo);
    }
    public function fileDownload()
    {
      return $this->repository->fileDownload();
    }

}
