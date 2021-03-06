<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterCode extends Model
{
    protected $table = 'letter_codes';

    protected $fillable = [
        'code',
        'title',
        'letter_code_id'
    ];

    public $timestamps = false;

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public static function getLetterCodes()
    {
        return LetterCode::where('letter_code_id', null);
    }

    public function sub_letter_codes()
    {
        return $this->hasMany(LetterCode::class);
    }

    public function letter_code()
    {
        return $this->belongsTo(LetterCode::class);
    }

    public function getLetterCodeNameAttribute()
    {
        $name = $this->code . '. ' . $this->title;

        if ($this->letter_code)
            $name = $this->letter_code->code . '.' . $this->code . ' ' . $this->title;

        return $name;
    }

    public static function getCode($id)
    {
        $letterCode = LetterCode::find($id);
        if ($letterCode->letter_code) return $letterCode->letter_code->code . '.' . $letterCode->code;
        else return $letterCode->code;
    }
}
