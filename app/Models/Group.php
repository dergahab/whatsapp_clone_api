<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Group extends Model
{
    // use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = ['uuid', 'name'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function users()
    {
        return $this->hasMany(GroupUser::class);
    }
}
