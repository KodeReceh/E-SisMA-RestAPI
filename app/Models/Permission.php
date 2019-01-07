<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'can'
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_permissions');
    }
}
