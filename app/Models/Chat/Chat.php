<?php

namespace App\Models\Chat;

use App\Models\Base;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Chat extends Base
{
    use SoftDeletes;

    protected $table = 'chats';

    protected $fillable = [
        'uuid',
        'user1',
        'user2',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'user1',
        'user2',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user1');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user2');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    public function message(): HasOne
    {
        return $this->hasOne(Message::class, 'chat_id', 'id')->latest();
    }

    public function sendBy(): BelongsTo
    {
        if (Auth::user()?->id != $this->user1) {
            return $this->userOne();
        }

        return $this->userTwo();
    }

	public function unread_messages(): HasMany
	{
		return $this->hasMany(Message::class, 'chat_id', 'id')->where('status', 1);
	}
}
