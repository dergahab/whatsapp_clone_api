<?php

namespace App\Models\Read;

use App\Models\User;
use App\Models\Chat\Message;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{

    protected $table = 'message_reads';

    protected $fillable = ['message_id', 'user_id', 'read_at'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
