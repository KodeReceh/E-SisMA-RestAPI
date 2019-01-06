<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveType extends Model
{
    protected $table = 'archive_types';

    public $timestamps = false;

    protected $fillable = [
        'type',
    ];

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
