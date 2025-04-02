<?php

namespace App\Models;

use App\Models\Chat\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatPrivate extends Model
{
    // use SoftDeletes;

    protected $table = 'chat_private';

    protected $fillable = ['uuid', 'message_id', 'from_user', 'to_user'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function message()
    {
        return $this->belongsTo(Chat::class, 'message_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'message_id');
    }
}
