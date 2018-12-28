<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $table = 'letter_templates';

    protected $appends = [
        'template_name',
        'villager_name',
    ];

    protected $fillable = [
        'template_id',
        'data',
        'status'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function villager()
    {
        return $this->belongsTo(Villager::class);
    }

    public function getTemplateNameAttribute()
    {
        return $this->template->title;
    }

    public function getVillagerNameAttribute()
    {
        return $this->villager ? $this->villager->name : 'Tidak ada';
    }


}
