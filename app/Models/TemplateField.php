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

    protected $appends = [
        'type_name',
        'role_name'
    ];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getTypeNameAttribute()
    {
        $types = config('esisma.field_types');
        try {
            return $types[$this->type];
        } catch (\Throwable $th) {
            return 'Tidak diketahui.';
        }

        return 'Tidak diketahui.';
    }

    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->title : 'Tidak Diperlukan';
    }
}
