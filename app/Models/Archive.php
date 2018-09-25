<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = [
        'archive_title',
        'archive_date',
        'department_id',
        'archive_type_id',
        'description'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function archive_type()
    {
        return $this->belongsTo(ArchiveType::class);
    }
}
