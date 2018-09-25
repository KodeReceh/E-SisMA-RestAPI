<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutcomingLetter extends Model
{
    protected $table = 'outcoming_letters';

    protected $timestamps = false;

    protected $fillable = [
        'letter_id',
        'ordinal'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
