<?php

namespace App\Models;

use App\Models\Chat\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatGroup extends Model
{
    // use SoftDeletes;

    protected $table = 'chat_group';

    protected $fillable = ['uuid', 'message_id', 'from_group_user', 'to_group_user'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function message()
    {
        return $this->belongsTo(Chat::class, 'message_id');
    }

    public function fromGroupUser()
    {
        return $this->belongsTo(GroupUser::class, 'from_group_user')->with('user');
    }

    public function toGroupUser()
    {
        return $this->belongsTo(GroupUser::class, 'to_group_user');
    }

    public function messages()
    {
        return $this->belongsTo(Chat::class, 'message_id');
    }
    public function fromUser()
    {
        return $this->belongsTo(GroupUser::class, 'from_group_user');
    }
    public function toUser()
    {
        return $this->belongsTo(GroupUser::class, 'to_group_user');
    }
}
