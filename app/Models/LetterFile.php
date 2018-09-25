<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterFile extends Model
{
    protected $table = 'letter_documents';

    protected $fillable = [
        'letter_id',
        'file_id',
    ];

    protected $timestamps = false;

    protected $increamenting = false;

    protected $primaryKey = null;

}
