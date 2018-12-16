<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterCode extends Model
{
    protected $table = 'letter_codes';

    protected $fillable = [
        'code',
        'title',
        'letter_code_id'
    ];

    public $timestamps = false;

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public static function getLeterCodes()
    {
        return LetterCode::where('letter_code_id', null);
    }

    public function sub_letter_codes()
    {
        return $this->hasMany(LetterCode::class);
    }
}
