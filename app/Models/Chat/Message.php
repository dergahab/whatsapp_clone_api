<?php

namespace App\Models\Chat;

use App\Models\ChatGroup;
use App\Models\ChatPrivate;
use App\Models\Str;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $fillable = [
        'uuid',
        'user1',
        'user2'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function privateMessage()
    {
        return $this->hasOne(ChatPrivate::class);
    }

    public function groupMessage()
    {
        return $this->hasOne(ChatGroup::class);
    }
}
