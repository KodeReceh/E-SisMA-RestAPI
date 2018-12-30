<?php

namespace App\Http\Controllers;

use App\Models\LetterCode;
use Illuminate\Support\Facades\DB;

class LetterCodeController extends Controller
{
    public function getLetterCodeList()
    {
        $letterCodes = LetterCode::getLetterCodes()->select(
            'id',
            DB::raw("CONCAT(letter_codes.code,' - ',letter_codes.title) as code_title")
        )->get();

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $letterCodes
        ], 200);
    }

    public function getSubLetterCodeList($letter_code)
    {
        $letterCode = LetterCode::find($letter_code);
        $subLetterCodes = $letterCode->sub_letter_codes()
            ->select('id', DB::raw("CONCAT(letter_codes.code,' - ',letter_codes.title) as code_title"))
            ->get();

        return response()->json([
            'success' => true,
            'description' => 'Data berhasil diambil.',
            'data' => $subLetterCodes
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

    public function getLetterCodeName($id)
    {
        $code = LetterCode::find($id);
        $name = $code->code . '. ' . $code->title;

        if ($code->letter_code)
            $name = $code->letter_code->code . '.' . $code->code . ' ' . $code->title;


        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $name
        ], 200);
    }
}
