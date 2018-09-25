<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubLetterCode extends Model
{
    protected $table = 'sub_sub_letter_codes';

    protected $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'sub_letter_code_id'
    ];

    public function sub_letter_code()
    {
        return $this->belongsTo(SubLetterCode::class);
    }
}
