<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'title',
        'description'
    ];

    public $timestamps = false;

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
