<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\IncomingLetter;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\File;

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
        $letter->attachments = 1; //$request->attachments;
        $letter->to = $request->to;
        $letter->letter_code_id = $request->letter_code_id;
        $letter->sub_letter_code_id = $request->sub_letter_code_id ?: null;
        $letter->save();

        $incomingLetter = new IncomingLetter([
            'sender' => $request->sender,
            'receipt_date' => $request->receipt_date,
            'ordinal' => 1
        ]);

        $letter->incoming_letter()->save($incomingLetter);

        // if($request->has('files')){
        //     $document = new Document();
        //     $document->title = "Surat Masuk ".$letter->subject;
        //     $document->date = $letter->date;
        //     $document->description = $letter->tendency;
        //     $document->save();

        //     foreach ($request->file('files') as $key => $file) {
        //         $ext = $file->getClientOriginalExtension();
        //         $fileName = $letter->id.'-'.$key.'.'.$ext;
        //         $file->storeAs($this->configDiskStorage, $fileName);
        //         $fileInst = new File([
        //             'path' => $fileName,
        //             'caption' => $document->title.'-'.$key,
        //             'ordinal' => $key
        //         ]);

        //         $document->files()->save($fileInst);
        //     }

        //     $letter->document()->associate($document);
        //     $letter->save();
            
        // }

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil disimpan.',
            'data' => $incomingLetter,
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
}
