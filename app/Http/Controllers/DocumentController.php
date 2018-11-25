<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

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
        if($document = Document::create($request->all())){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menyimpan data.',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan data.'
        ], 417);
    }


    public function update($id, Request $request)
    {
        $document = Document::find($id);

        if($document->update($request->all())){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil update data.',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan data.'
        ], 417);
    }

    public function delete($id)
    {
        $document = Document::find($id);

        if($document->delete()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menghapus data.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.'
        ], 417);
    }
}
