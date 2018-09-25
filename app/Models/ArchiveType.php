<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveType extends Model
{
    protected $table = 'archive_types';

    protected $timestamps  = false;

    protected $fillable = [
        'type',
        'description'
    ];

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
