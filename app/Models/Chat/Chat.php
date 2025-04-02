<?php

namespace App\Models\Chat;

use App\Models\Base;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Base
{
    use SoftDeletes;
    protected $table = "chats";
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
     public function userOne()
    {
        return $this->belongsTo(User::class, 'user1');
    }
    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user2');
    }

//    public function message(): HasOne
//    {
//        return $this->belongsTo(User::class, 'user2');
//    }
//
//    public function messages(): HasOne
//    {
//        return $this->belongsTo(User::class, 'user2');
//    }
}
