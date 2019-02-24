<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IncomingLetter extends Model
{
    protected $table = 'incoming_letters';

    protected $primaryKey = 'letter_id';

    public $timestamps = false;

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

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'incoming_letter_id', 'letter_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'dispositions', 'incoming_letter_id', 'user_id');
    }

    public static function getOrdinal($date)
    {
        $theYear = Carbon::parse($date)->year;
        $last = IncomingLetter::join('letters', 'letters.id', '=', 'incoming_letters.letter_id')
            ->whereYear('date', $theYear)
            ->orderBy('ordinal', 'desc')
            ->select('ordinal')
            ->first();

        if (!$last) $last = 0;
        else $last = $last->ordinal;

        return $last + 1;
    }
}
