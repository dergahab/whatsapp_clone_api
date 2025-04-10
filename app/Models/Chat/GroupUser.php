<?php

namespace App\Models\Chat;


use App\Models\Base;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GroupUser extends Base
{
     use SoftDeletes;

    protected $table = 'group_users';

    protected $fillable = [
        'uuid',
        'user_id',
        'group_id'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function group()
    {
        return $this->belongsToMany(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
