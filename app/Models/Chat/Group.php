<?php

namespace App\Models\Chat;

use App\Models\Base;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Group extends Base
{
    use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'uuid',
        'name',
        'image',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, rp_get_table(GroupUser::class), 'group_id', 'user_id');
    }
}
