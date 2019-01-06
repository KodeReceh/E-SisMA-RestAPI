<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $appends = ['type'];

    protected $fillable = [
        'title',
        'role_id',
        'archive_type_id',
        'description'
    ];

    public function archive_type()
    {
        return $this->belongsTo(ArchiveType::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getTypeAttribute()
    {
        return $this->archive_type->type;
    }
}
