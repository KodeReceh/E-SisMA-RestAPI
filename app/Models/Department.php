<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'department_code',
        'department_name',
        'description'
    ];

    protected $timestamps = false;

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments', 'department_id', 'user_id')
                ->withPivot('status');
    }
}
