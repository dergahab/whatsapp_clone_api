<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    use SoftDeletes;

    protected $table = 'groups';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'bigint';

    protected $fillable = [
        'uuid',
        'name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->uuid)) {
                $group->uuid = (string) Str::uuid();
            }
        });
    }
}
