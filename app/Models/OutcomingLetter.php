<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutcomingLetter extends Model
{
    protected $table = 'outcoming_letters';

    protected $primaryKey = 'letter_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'letter_id',
        'recipient',
        'ordinal'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
