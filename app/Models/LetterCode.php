<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterCode extends Model
{
    protected $table = 'letter_codes';

    protected $fillable = [
        'letter_code',
        'code_title'
    ];

    protected $timestamps = false;

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }
}
