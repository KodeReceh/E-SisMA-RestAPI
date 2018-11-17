<?php

namespace App\Http\Controllers;
use App\Models\LetterCode;
use Illuminate\Support\Facades\DB;

class LetterCodeController extends Controller
{
    public function getList() 
    {
        $letterCodes = LetterCode::select(
                        'id',
                        DB::raw("CONCAT(letter_codes.code,' - ',letter_codes.title) as code_title")
                        )->get();
        
        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $letterCodes
        ], 200);
    }

    public function get($id)
    {
        $letteCode = LetterCode::find($id);

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil',
            'data' => $letteCode
        ], 200);
    }
}
