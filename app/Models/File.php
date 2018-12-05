<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'path',
        'document_id',
        'caption',
        'ordinal'
    ];

    public $timestamps = true;

    protected $appends = ['file_extension'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function getPathFileAttribute()
    {
        if($letter = $this->document->letter){
            if($letter->incoming_letter){
                return config('esisma.dokumen.surat.masuk').'/'.$this->path;
            }else{
                return config('esisma.dokumen.surat.keluar').'/'.$this->path;
            }
        }else{
            return config('esisma.dokumen.general').'/'.$this->path;
        }

        return null;
    }
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->path_file, PATHINFO_EXTENSION);
    }
}
