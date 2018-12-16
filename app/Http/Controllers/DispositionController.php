<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposition;
use App\Models\IncomingLetter;

class DispositionController extends Controller
{
    public function storeDisposition($id, Request $request)
    {
        $data = $request->all();
        $data['user_id'] = app('auth')->user()->id;
        $data['incoming_letter_id'] = $id;
        $disposition = Disposition::create($data);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil disimpan.',
            'data' => $disposition
        ], 201);
    }

    public function updateDisposition($id, Request $request)
    {
        $userId = app('auth')->user()->id;
        $disposition = Disposition::where(['incoming_letter_id' => $id, 'user_id' => $userId])->first();

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
        $disposition = Disposition::where(['incoming_letter_id' => $id, 'user_id' => $userId])->first();

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
