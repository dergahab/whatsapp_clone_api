<?php
namespace App\Models\Vault;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Base;
use App\Models\User;

class UserPassword extends Base
{
    use SoftDeletes;

    protected $table = 'user_passwords';

    protected $fillable = [
        'uuid',
        'user_id',
        'password_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'create_by',
        'modify_by',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function password()
    {
        return $this->belongsTo(Password::class);
    }
}
