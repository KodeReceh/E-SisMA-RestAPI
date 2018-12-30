<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('archive')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $documents
        ], 200);
    }

    public function get($id)
    {
        $document = Document::find($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $document
        ], 200);
    }

    public function store(Request $request)
    {
        $document = new Document();
        $document->title = $request->title;
        $document->date = $request->date;
        $document->description = $request->description;
        $theFile = $request->file('file');
        $ext = $theFile->getClientOriginalExtension();
        $fileName = 'document-' . time() . '.' . $ext;
        $theFile->storeAs($document->path_file, $fileName);
        $document->path = $fileName;
        $document->file_type = $request->file_type;

        if ($document->save()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }


    public function update($id, Request $request)
    {
        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->date = $request->date;
        $document->description = $request->description;

        if ($request->hasFile('file')) {
            if (Storage::exists($document->path_file)) {
                Storage::delete($document->path_file);
            }
            $document->path = '';
            $theFile = $request->file('file');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'document-' . time() . '.' . $ext;
            $theFile->storeAs($document->path_file, $fileName);
            $document->path = $fileName;
            $document->file_type = $request->file_type;
        }

        if ($document->update()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }

    public function delete($id)
    {
        $document = Document::find($id);
        $path = $document->path_file;

        if ($document->delete()) {
            if (Storage::exists($path)) Storage::delete($path);
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dihapus.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.'
        ], 417);
    }

    public function responseFile($path)
    {
        $document = Document::where('path', $path)->first();
        $headers = [
            'Content-Type' => $document->file_type,
            'content-disposition' => 'attachment'
        ];

        return response()->download(
            storage_path('app/' . $document->path_file),
            $document->title . '.' . pathinfo($document->path_file, PATHINFO_EXTENSION),
            $headers
        );
    }
}
