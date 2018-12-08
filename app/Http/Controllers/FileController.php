<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $theFile->storeAs($file->path_file, $fileName);
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
        $path = $file->path_file;
        if($file->delete()){
            if(Storage::exists($path)) Storage::delete($path);
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
        if($request->file){
            if(Storage::exists($file->path_file)){
                Storage::delete($file->path_file);
            }
            $file->path = '';
            $theFile = $request->file('file');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = $file->document_id.'-'.time().'.'.$ext;
            $theFile->storeAs($file->path_file, $fileName);
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

    public function responseFile($path)
    {
        $file = File::where('path', $path)->first();
        $headers = [
            'Content-Type' => 'application/*',
            'content-disposition'              => 'attachment'
        ];

        return response()->download(
            storage_path('app/'.$file->path_file), 
            $file->document->title.'('.$file->caption.').'.pathinfo($file->path_file, PATHINFO_EXTENSION),
            $headers
        );
    }
}
