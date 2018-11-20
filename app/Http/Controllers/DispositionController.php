<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposition;
use App\Models\IncomingLetter;

class DispositionController extends Controller
{
    public function storeDisposition($id, Request $request)
    {
        $incomingLetter = IncomingLetter::find($id);
        $disposition = new Disposition([
            'summary' => $request->summary,
            'processing_date' => $request->processing_date,
            'information' => $request->information,
            'user_id' => $request->user->id
        ]);

        $incomingLetter->disposition()->save($disposition);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil disimpan.',
            'data' => $incomingLetter
        ], 200);
    }

    public function updateDisposition($id, Request $request)
    {
        $disposition = Disposition::find($id);
        if($disposition->update($request->all())){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil diupdate.',
                'data' => $disposition
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal mengupdate.'
        ], 417);
    }

    public function get($id)
    {
        $disposition = Disposition::find($id);
        return response()->json([
            'success' => true,
            'description' => 'Berhasil ambil data.',
            'data' => $disposition
        ], 200);
    }
}
