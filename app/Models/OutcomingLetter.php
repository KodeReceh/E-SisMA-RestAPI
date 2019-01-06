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

    public static function getOrdinal($year)
    {
        $lastOrdinal = 0;

        $last = OutcomingLetter::join('letters', 'letters.id', '=', 'outcoming_letters.letter_id')
            ->whereYear('date', $year)
            ->orderBy('ordinal', 'desc')
            ->select('ordinal')
            ->first();

        if ($last) $lastOrdinal = $last->ordinal; 

        return $lastOrdinal + 1;
    }
}
