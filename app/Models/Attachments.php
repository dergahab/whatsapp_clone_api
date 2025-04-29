<?php

namespace App\Models;

use App\Models\Chat\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'message_id',
        'id',
        'created_at',
        'updated_at',
    ];

    public function messages():BelongsTo
    {
        return $this->belongsTo(Message::class);
    }


}
