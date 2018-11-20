<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposition;
use App\Models\IncomingLetter;

class DispositionController extends Controller
{
    public function storeDisposition($id, Request $request)
    {
        $disposition = Disposition::create($request->all());

        return response()->json([
            'success' => true,
            'description' => 'Berhasil disimpan.',
            'data' => $disposition
        ], 201);
    }

    public function updateDisposition($id, Request $request)
    {
        $disposition = Disposition::find($id);

        if($disposition->update($request->all())){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil diupdate.',
                'data' => $disposition
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal mengupdate.'
        ], 417);
    }

    public function get($id)
    {
        $disposition = Disposition::find($id);

        if($disposition){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil ambil data.',
                'data' => $disposition
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Data tidak ditemukan.'
        ], 404);
        
    }
}
