<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    protected $table = 'incoming_letters';

    protected $primaryKey = 'letter_id';

    public $timestamps  = false;

    public $incrementing = false;

    protected $fillable = [
        'sender',
        'receipt_date',
        'ordinal',
        'letter_id'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function disposition()
    {
        return $this->hasOne(Disposition::class, 'incoming_letter_id',  'letter_id');
    }
}
