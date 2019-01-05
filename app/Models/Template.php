<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';

    protected $fillable = [
        'title',
        'needs_villager_data',
        'template_file',
        'letter_code_id'
    ];

    protected $appends = [
        'needs_villager_data_string',
        'sub_letter_code_id',
    ];

    public function template_fields()
    {
        return $this->hasMany(TemplateField::class);
    }

    public function letter_templates()
    {
        return $this->hasMany(LetterTemplate::class);
    }

    public function letter_code()
    {
        return $this->belongsTo(LetterCode::class, 'letter_code_id', 'id');
    }

    public function getSubLetterCodeIdAttribute()
    {
        if ($this->letter_code->letter_code_id) return $this->letter_code->id;

        return '';
    }

    public function getNeedsVillagerDataStringAttribute()
    {
        return $this->needs_villager_data ? 'Ya' : 'Tidak';
    }

    public function getCleanData()
    {
        if ($this->sub_letter_code_id)
            $this->letter_code_id = $this->letter_code->letter_code->id;

        return $this;
    }


}
