<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutcomingLetter;

class VerifyLetterController extends Controller
{
    public function check(Request $request)
    {
        $outcomingLetter = OutcomingLetter::join('letters', 'letters.id', '=', 'outcoming_letters.letter_id')
            ->select('letters.date', 'letters.number', 'outcoming_letters.recipient', 'letters.subject')
            ->where(['letters.number' => $request->number, 'letters.date' => $request->date])
            ->first();
        if ($outcomingLetter) $outcomingLetter->date = \Helpers::translateDate($outcomingLetter->date);
        return response()->json([
            'success' => true,
            'data' => $outcomingLetter
        ], 200);
    }
}
