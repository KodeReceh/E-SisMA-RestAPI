<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villager extends Model
{
    protected $table = 'villagers';

    protected $appends = ['select_name'];

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

    public function  getSelectNameAttribute()
    {
        return $this->name.' - '.$this->NIK;
    }

    public static function getFIelds()
    {
        return config('esisma.villager_field');
    }
}
