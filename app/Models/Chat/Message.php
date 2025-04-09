<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Message extends Model
{
    use SoftDeletes;
    protected $table = 'messages';
    protected $fillable = [
        'uuid',
        'create_by',
        'message',
        'chat_id',
    ];
    protected $hidden = [
        'id',
        'chat_id',
        'updated_at',
        'deleted_at',
    ];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
    protected function createBy(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == Auth::user()?->id ? 'sender' : 'receiver'
        );
    }
	public function creator(): BelongsTo
	{
		return $this->belongsTo(User::class,  'create_by', 'id');
	}
    protected function editStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == 0 ? false : true
        );
    }
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                switch ($value) {
                    case 0:
                        return 'sended';
                    case 1:
                        return 'accepted';
                    case 2:
                        return 'readed';
                    default:
                        return 'unnknown';
                }
            }
        );
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->isToday()
                ? Carbon::parse($value)->format('H:i')
                : Carbon::parse($value)->diffForHumans()
        );
    }
}
