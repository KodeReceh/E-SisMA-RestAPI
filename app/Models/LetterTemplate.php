<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $table = 'letter_templates';

    protected $fillable = [
        'template_id',
        'data',
        'status'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }


}
