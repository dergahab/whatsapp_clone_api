<?php

namespace App\Models;

use App\Models\Chat\Message;

class Attachments extends Base
{
    protected $table = 'attachments';

    protected $fillable = [
        'message_id',
        'attachment_type',
        'name',
        'path',
        'size'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function messages()
    {
        return $this->belongsTo(Message::class);
    }


}
