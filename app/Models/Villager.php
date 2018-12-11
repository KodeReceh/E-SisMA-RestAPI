<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villager extends Model
{
    protected $table = 'villagers';

    protected $fillable = [
        'name',
        'birthplace',
        'job',
        'religion',
        'tribe',
        'NIK',
        'status',
        'address',
        'photo'
    ];
}
