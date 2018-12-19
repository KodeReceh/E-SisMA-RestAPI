<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\Disposition;

class RecipientController extends Controller
{
    public function getRecipients($letter_id)
    {
        $incomingLetter = IncomingLetter::where('letter_id', $letter_id)->first();
        $data = $incomingLetter->dispositions()->join('users', 'dispositions.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->select('users_id', 'users.name as name', 'roles.title as role')
                ->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $data
        ], 200);
    }

    public function get($letter_id, $user_id)
    {
        $recipient = Disposition::where([
            'incoming_letter_id' => $letter_id,
            'user_id' => $user_id
        ])->join('users', 'users.id', '=', 'dispositions.user_id')
        ->join('roles', 'roles.id', '=', 'users.role_id')
        ->select('users_id', 'users.name as name', 'roles.title as role')
        ->first();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil Mengambil data.',
            'data' => $recipient
        ], 200);
    }

    public function store($letter_id, Request $request)
    {
        $incomingLetter = IncomingLetter::where('letter_id', $letter_id)->first();

        if($incomingLetter->users()->attach($request->user_id)){
            return response()->json([
                'success' => true,
                'descripiton' => 'Berhasil menambahkan data.'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menambahkan data.'
        ], 417);
    }

    public function delete($letter_id, $user_id)
    {
        $recipient = Disposition::where(['incoming_letter_id' => $letter_id, 'user_id' => $user_id])->first();

        if($recipient->delete()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menghapus data.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'desciption' => 'Gagal menghapus data.'
        ], 417);
    }
}
