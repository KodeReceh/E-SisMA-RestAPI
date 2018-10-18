<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\OutcomingLetter;

class OutcomingLetterController extends Controller
{
    public function store(Request $request)
    {
        $letter = new Letter();
        $letter->number = $request->number;
        $letter->date = $request->date;
        $letter->subject = $request->subject;
        $letter->tendency = $request->tendency;
        $letter->attachments = $request->attachments;
        $letter->letter_code_id = $request->letter_code_id;
        $letter->sub_letter_code_id = $request->sub_letter_code_id ?: null;
        $letter->to = $request->to;
        $letter->save();

        $outcomingLetter = new OutcomingLetter([
            'ordinal' => $request->ordinal
        ]);

        $letter->outcoming_letter()->save($outcomingLetter);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $outcomingLetter->letter()->with('outcoming_letter')
                                     ->with('letter_code')
                                     ->with('sub_letter_code')
                                     ->get()
        ], 200);
    }
}
