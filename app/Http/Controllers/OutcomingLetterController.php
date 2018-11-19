<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\OutcomingLetter;

class OutcomingLetterController extends Controller
{
    public function index()
    {
        $outcomingLetters = OutcomingLetter::with('letter')
                                         ->with('letter.letter_code')
                                         ->with('letter.sub_letter_code')
                                         ->with('letter.document.files')
                                         ->get();
        
        return response()->json([
            'success' => true,
            'amount_of_data' => $outcomingLetters->count(),
            'data' => $outcomingLetters
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
        $letter->to = $request->to;
        $letter->save();

        $outcomingLetter = new OutcomingLetter([
            'ordinal' => 1 //$request->ordinal
        ]);

        $letter->outcoming_letter()->save($outcomingLetter);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $outcomingLetter
        ], 200);
    }

    public function getList()
    {
        $outcomingLetters = OutcomingLetter::join('letters', 'outcoming_letters.letter_id', 'letters.id')
                                        ->select(
                                            'letters.id as id',
                                            'letters.number as number',
                                            'letters.date as date',
                                            'letters.subject as subject',
                                            'letters.tendency as tendency',
                                            'letters.to as to'
                                            )
                                        ->get();
        
        return response()->json([
            'success' => true,
            'amount_of_data' => $outcomingLetters->count(),
            'data' => $outcomingLetters
        ], 200);
    }

    public function update($id, Request $request)
    {
        $letter = Letter::find($id);
        $letter->number = $request->number;
        $letter->date = $request->date;
        $letter->subject = $request->subject;
        $letter->tendency = $request->tendency;
        $letter->attachments = $request->attachments;
        $letter->to = $request->to;
        $letter->letter_code_id = $request->letter_code_id;
        $letter->sub_letter_code_id = $request->sub_letter_code_id ?: null;
        $letter->save();

        $letter->outcoming_letter->update([
            'ordinal' => 1
        ]);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $letter->outcoming_letter,
        ], 200);

    }

    public function get($id)
    {
        $outcomingLetter = OutcomingLetter::where('letter_id', $id)
                                        ->join('letters', 'outcoming_letters.letter_id', 'letters.id')
                                        ->select(
                                            'id',
                                            'number',
                                            'date',
                                            'subject',
                                            'tendency',
                                            'to',
                                            'attachments',
                                            'letter_code_id',
                                            'sub_letter_code_id'
                                        )
                                        ->first();
        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil',
            'data' => $outcomingLetter
        ], 200);
    }

    public function delete($id) {
        $outcomingLetter = OutcomingLetter::find($id);

        if($outcomingLetter->delete()){
            return response()->json([
                'success' => true,
                'description' => 'Data berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Ada kesalahan,data gagal dihapus.'
        ], 417);
    }
}
