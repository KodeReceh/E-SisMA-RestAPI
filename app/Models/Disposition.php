<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Disposition extends Model
{
    protected $table = 'dispositions';

    public $incrementing = false;

    protected $primaryKey = ['incoming_letter_id', 'user_id'];

    public $timestamps = false;

    protected $appends = ['processing_date_formatted'];

    protected $fillable = [
        'incoming_letter_id',
        'summary',
        'processing_date',
        'information',
        'user_id',
        'status'
    ];

    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach($this->primaryKey as $pk) {
            $query = $query->where($pk, $this->attributes[$pk]);
        }
        return $query;
    }

    public function incoming_letter()
    {
        return $this->belongsTo(IncomingLetter::class, 'incoming_letter_id', 'letter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function isClientRecipient($letterId)
    {
        $userId = app('auth')->user()->id;
        $letter = IncomingLetter::find($letterId);

        if($letter){
            if($letter->dispositions()->where('user_id', $userId)->first())
                return true;
        } 
        
        return false;
    }

    public function getProcessingDateFormattedAttribute()
    {
        return \Helpers::translateDate($this->processing_date);
    }
}
