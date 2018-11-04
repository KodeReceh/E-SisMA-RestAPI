<?php

namespace App\Http\Controllers;
use App\Models\SubLetterCode;
use Illuminate\Support\Facades\DB;

class SubLetterCodeController extends Controller
{
    public function getByLetterCodeId($id) 
    {
        $subLetterCodes = SubLetterCode::where('letter_code_id', $id)
                            ->select('id', DB::raw("CONCAT(sub_letter_codes.code,' - ',sub_letter_codes.title) as code_title"))
                            ->get();
        
        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $subLetterCodes
        ], 200);
    }
}
