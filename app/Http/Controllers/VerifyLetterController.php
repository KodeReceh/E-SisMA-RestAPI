<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutcomingLetter;

class VerifyLetterController extends Controller
{
    public function check (Request $request)
    {
        $outcomingLetter = OutcomingLetter::join('letters', 'letters.id', '=', 'outcoming_letters.letter_id')
                            ->where(['letters.date' => $request->date, 'letters.date' => $request->date])
                            ->get();

        if ($outcomingLetter) 
            return response()->json([
                'success' => true,
                'description' => 'Data ditemukan!',
                'data' => $outcomingLetter
            ], 200);
        
        return response()->json([
            'success' => false,
            'description' => 'Data tidak ditemukan!',
            'data' => null
        ], 404);
    }
}
