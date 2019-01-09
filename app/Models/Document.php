<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'path',
        'date',
        'description',
        'archive_id',
        'file_type',
        'uploader_id'
    ];

    protected $appends = ['file_extension', 'date_formatted'];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function letter()
    {
        return $this->hasOne(Letter::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function getPathFile()
    {
        if ($letter = $this->letter) {
            if ($letter->incoming_letter) {
                return config('esisma.dokumen.surat.masuk');
            } else {
                return config('esisma.dokumen.surat.keluar');
            }
        } else {
            return config('esisma.dokumen.general');
        }

        return null;
    }

    public function getPathFileAttribute()
    {
        if ($letter = $this->letter) {
            if ($letter->incoming_letter) {
                return config('esisma.dokumen.surat.masuk') . '/' . $this->path;
            } else {
                return config('esisma.dokumen.surat.keluar') . '/' . $this->path;
            }
        } else {
            return config('esisma.dokumen.general') . '/' . $this->path;
        }

        return null;
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->path_file, PATHINFO_EXTENSION);
    }

    public function getDateFormattedAttribute()
    {
        return \Helpers::translateDate($this->date);
    }    
}
