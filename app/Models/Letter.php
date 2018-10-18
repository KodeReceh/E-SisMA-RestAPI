<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $table = 'letters';

    protected $fillable = [
        'number',
        'date',
        'subject',
        'tendency',
        'attachments',
        'to',
        'letter_code_id'
    ];

    public function letter_code()
    {
        return $this->belongsTo(LetterCode::class);
    }

    public function sub_letter_code()
    {
        return $this->belongsTo(SubLetterCode::class);
    }

    public function incoming_letter()
    {
        return $this->hasOne(IncomingLetter::class);
    }

    public function outcoming_letter()
    {
        return $this->hasOne(OutcomingLetter::class);
    }

    public function letter_files()
    {
        return $this->hasMany(LetterFile::class);
    }
}
