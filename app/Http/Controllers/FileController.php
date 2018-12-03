<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $file = new File();
        $file->document_id = $request->document_id;
        $file->ordinal = $request->ordinal;
        $file->caption = $request->caption;
        $theFile = $request->file('file');
        $ext = $theFile->getClientOriginalExtension();
        $fileName = $file->document_id.'-'.time().'.'.$ext;
        $theFile->storeAs(config('esisma.dokumen.surat.masuk'), $fileName);
        $file->path = $fileName;

        if($file->save()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $file
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }

    public function delete($id)
    {
        $file = File::find($id);

        if($file->delete()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dihapus.',
            ], 200);
        }
    }

    public function getListByDocument($document)
    {
        $files = File::where('document_id', $document)->orderBy('ordinal', 'asc')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $files
        ], 200);
    }

    public function lastOrdinal($document)
    {
        $file = File::where('document_id', $document)->orderBy('ordinal', 'desc')->first();

        if($file){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil mengambil data',
                'data' => $file->ordinal
            ], 200);
        }

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data',
            'data' => 0,
        ], 200);
    }

    public function get($id)
    {
        $file = File::find($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $file
        ], 200);
    }

    public function update($id, Request $request)
    {
        $file = File::find($id);
        $file->document_id = $request->document_id;
        $file->ordinal = $request->ordinal;
        $file->caption = $request->caption;
        if($theFile = $request->file('file')){
            $ext = $theFile->getClientOriginalExtension();
            $fileName = $letter->id.'-'.$key.'.'.$ext;
            $fitheFilele->storeAs(config('esisma.dokumen.surat.masuk'), $fileName);
            $file->path = $fileName;
        }
        if($file->update()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $file
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }
}
