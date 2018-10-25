<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterFile extends Model
{
    protected $table = 'letter_files';

    protected $fillable = [
        'letter_id',
        'file_id',
    ];

    public $timestamps = false;

    protected $increamenting = false;

    protected $primaryKey = null;

}
