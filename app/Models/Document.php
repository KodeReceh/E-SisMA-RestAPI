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
        'public',
        'file_type',
        'uploader_id'
    ];

    protected $appends = [
        'file_extension',
        'date_formatted',
        'public_text',
        'this_user_can_manage_the_doc'
    ];

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

    public function getPublicTextAttribute()
    {
        return $this->public ? "Iya" : "Tidak";
    }

    public function getThisUserCanManageTheDocAttribute()
    {
        $user = app('auth')->user();

        if ($this->uploader_id == $user->id) return true;

        if ($this->archive) {
            if ($this->archive->role_id == $user->role_id) return true;
        }

        return false;
    }
}
