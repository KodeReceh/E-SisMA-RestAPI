<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubLetterCode extends Model
{
    protected $table = 'sub_letter_codes';

    protected $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'letter_code_id'
    ];

    public function letter_code()
    {
        return $this->belongsTo(LetterCode::class);
    }

    public function sub_sub_letter_codes()
    {
        return $this->hasMany(SubSubLetterCode::class);
    }
}
