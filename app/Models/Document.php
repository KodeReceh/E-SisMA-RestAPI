<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'date',
        'description',
        'archive_id'
    ];


    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function letter()
    {
        return $this->hasOne(Letter::class);
    }
    public function getPathFile()
    {
        if($letter = $this->letter){
            if($letter->incoming_letter){
                return config('esisma.dokumen.surat.masuk');
            }else{
                return config('esisma.dokumen.surat.keluar');
            }
        }else{
            return config('esisma.dokumen.general');
        }

        return null;
    }
}
