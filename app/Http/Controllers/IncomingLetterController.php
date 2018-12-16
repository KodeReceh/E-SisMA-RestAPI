<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\IncomingLetter;

class IncomingLetterController extends Controller
{
    private $configDiskStorage;

    public function __construct()
    {
        $this->configDiskStorage = config('esisma.dokumen.surat.masuk');
    }
    
    public function index()
    {
        $incomingLetters = IncomingLetter::with('disposition')
                                         ->with('letter')
                                         ->with('letter.letter_code')
                                         ->with('letter.sub_letter_code')
                                         ->with('letter.document.files')
                                         ->get();
        
        return response()->json([
            'success' => true,
            'amount_of_data' => $incomingLetters->count(),
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
        $letter->save();

        $incomingLetter = new IncomingLetter([
            'sender' => $request->sender,
            'receipt_date' => $request->receipt_date,
            'ordinal' => 1
        ]);

        $letter->incoming_letter()->save($incomingLetter);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $incomingLetter,
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
        $letter->letter_code_id = $request->letter_code_id;
        $letter->save();

        $letter->incoming_letter->update([
            'sender' => $request->sender,
            'receipt_date' => $request->receipt_date,
            'ordinal' => 1
        ]);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $letter->incoming_letter,
        ], 200);

    }

    public function getList()
    {
        $incomingLetters = IncomingLetter::join('letters', 'incoming_letters.letter_id', 'letters.id')
                                        ->select(
                                            'letters.id as id',
                                            'letters.number as number',
                                            'letters.date as date',
                                            'letters.subject as subject',
                                            'letters.tendency as tendency',
                                            'incoming_letters.sender as sender'
                                            )
                                        ->get();
        
        return response()->json([
            'success' => true,
            'amount_of_data' => $incomingLetters->count(),
            'data' => $incomingLetters
        ], 200);
    }

    public function get($id)
    {
        $incomingLetter = IncomingLetter::where('letter_id', $id)
                                        ->join('letters', 'incoming_letters.letter_id', 'letters.id')
                                        ->select(
                                            'id',
                                            'number',
                                            'date',
                                            'receipt_date',
                                            'subject',
                                            'tendency',
                                            'sender',
                                            'attachments',
                                            'letter_code_id',
                                            'sub_letter_code_id'
                                        )
                                        ->first();
        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil',
            'data' => $incomingLetter
        ], 200);
    }

    public function delete($id) {
        $incomingLetter = IncomingLetter::find($id);

        if($incomingLetter->delete()){
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
