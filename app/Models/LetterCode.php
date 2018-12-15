<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterCode extends Model
{
    protected $table = 'letter_codes';

    protected $fillable = [
        'code',
        'title'
    ];

    public $timestamps = false;

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function sub_letter_codes()
    {
        return $this->hasMany(LetterCode::class);
    }
}
