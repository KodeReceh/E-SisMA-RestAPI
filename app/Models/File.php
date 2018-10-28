<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'path',
        'document_id',
        'caption',
        'ordinal'
    ];

    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
