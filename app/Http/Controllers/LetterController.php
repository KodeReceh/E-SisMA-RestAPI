<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;

class LetterController extends Controller
{
    public function get($id)
    {
        $letter = Letter::with('incoming_letter')->with('outcoming_letter')->find($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $letter
        ], 200);
    }

    public function getNumbers()
    {
        $numbers = Letter::pluck('number');

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data',
            'data' => $numbers
        ], 200);
    }
}
