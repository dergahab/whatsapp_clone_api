<?php

namespace App\Models\Chat;

use App\Models\Base;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Base
{
    use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'uuid',
        'name',
        'file',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, rp_get_table(GroupUser::class), 'group_id', 'user_id');
    }

    public function receivers(): BelongsToMany
    {
        $userId = auth()->id();

        return $this->belongsToMany(
            User::class,
            rp_get_table(GroupUser::class),
            'group_id',
            'user_id'
        )->where('user_id', '!=', $userId);
    }

    public function message(): HasOne
    {
        return $this->hasOne(Message::class, 'group_id', 'id')->latest();
    }

    public function unread_messages(): HasMany
    {
        return $this->hasMany(Message::class, 'group_id', 'id')->where('status', 1);
    }
}
