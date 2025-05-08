<?php

namespace App\Models;

use App\Models\Chat\Chat;
use App\Models\Vault\Password;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'profile_picture',
        'name',
        'email',
        'password',
        'type',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'deleted_at',
        "pivot",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sendPasswordResetNotification($token)
    {

        $url = 'https://spa.test/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function chatAsUserOne()
    {
        return $this->hasMany(Chat::class, 'user1');
    }

    public function chatAsUserTwo()
    {
        return $this->hasMany(Chat::class, 'user2');
    }

    public function passwords()
    {
        return $this->belongsToMany(Password::class, 'user_passwords')
            ->withTimestamps()
            ->withPivot(['uuid']);
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                return $value == 0 ? 'user' : 'admin';
            }
        );
    }
}
