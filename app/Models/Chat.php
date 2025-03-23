<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Chat extends Model
{
    // use SoftDeletes;

    protected $table = 'chat';

    protected $fillable = [
        'uuid',
        'message',

    ];

    protected $hidden = [
        'id',
        'deleted_at',
        'updated_at',
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
