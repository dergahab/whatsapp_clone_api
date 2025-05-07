<?php

namespace App\Models\Vault;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Base;

class Password extends Base
{
    use SoftDeletes;

    protected $table = 'passwords';

    protected $fillable = [
        'uuid',
        'credential',
        'title',
        'description',
        'create_by',
        'modify_by',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'create_by',
        'modify_by',
        "pivot"
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modify_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_passwords')
            ->withTimestamps()
            ->withPivot(['uuid']);
    }
}
