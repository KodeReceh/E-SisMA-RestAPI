<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    protected $table = 'template_fields';

    protected $fillable = [
        'name',
        'template_id',
        'type',
        'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
