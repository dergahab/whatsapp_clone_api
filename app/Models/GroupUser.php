<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GroupUser extends Model
{
    // use SoftDeletes;

    protected $table = 'group_users';

    protected $fillable = ['uuid', 'user_id', 'group_id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groups()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
