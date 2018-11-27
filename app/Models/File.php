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

    public $timestamps = true;

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
