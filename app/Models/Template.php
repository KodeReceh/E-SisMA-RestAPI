<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';

    protected $fillable = [
        'title',
        'needs_villager_data',
        'template_file'
    ];

    protected $appends = ['needs_villager_data_string'];

    public function template_fields()
    {
        return $this->hasMany(TemplateField::class);
    }

    public function letter_templates()
    {
        return $this->hasMany(LetterTemplate::class);
    }

    public function getNeedsVillagerDataStringAttribute()
    {
        return $this->needs_villager_data ? 'Ya' : 'Tidak';
    }


}
