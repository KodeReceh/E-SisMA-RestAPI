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
        'letter_code_id',
        'sub_letter_code_id',
        'document_id'
    ];

    protected $appends = ['is_incoming_letter', 'is_outcoming_letter'];

    public function letter_code()
    {
        return $this->belongsTo(LetterCode::class);
    }

    public function incoming_letter()
    {
        return $this->hasOne(IncomingLetter::class);
    }

    public function outcoming_letter()
    {
        return $this->hasOne(OutcomingLetter::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function getDocument()
    {
        if ($this->document) return $this->document->id;

        return null;
    }

    public function getIsIncomingLetterAttribute()
    {
        if ($this->incoming_letter) return true;
        return false;
    }

    public function getIsOutcomingLetterAttribute()
    {
        if ($this->outcoming_letter) return true;
        return false;
    }
}
