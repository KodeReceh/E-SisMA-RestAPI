<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'date',
        'description',
        'archive_id'
    ];


    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
