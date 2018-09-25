<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $table = 'dispositions';

    protected $fillable = [
        'incoming_letter_id',
        'summary',
        'processing_date',
        'information',
        'user_id'
    ];

    public function incoming_letter()
    {
        return $this->belongsTo(IncomingLetter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
