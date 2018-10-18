<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\IncomingLetter;

class IncomingLetterController extends Controller
{
    public function index()
    {
        $incomingLetters = IncomingLetter::with('disposision')
                                         ->with('letter')
                                         ->with('letter_code')
                                         ->with('sub_letter_code')
                                         ->with('sub_sub_letter_code')
                                         ->get();
        
        return response()->json([
            'success' => true,
            'data' => $incomingLetters
        ], 200);
    }

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
        $letter->save();

        $incomingLetter = new IncomingLetter([
            'sender' => $request->sender,
            'receipt_date' => $request->receipt_date,
            'ordinal' => $request->ordinal
        ]);

        $letter->incoming_letter()->save($incomingLetter);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $incomingLetter->letter()->with('incoming_letter')
                                     ->with('letter_code')
                                     ->with('sub_letter_code')
                                     ->get()
        ], 200);

    }
}
