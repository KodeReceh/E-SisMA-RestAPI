<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function get()
    {
        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => app('auth')->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $profile = app('auth')->user();
        $profile->name = $request->name;
        $profile->employee_id_number = $request->employee_id_number;
        $profile->email = $request->email;
        $profile->address = $request->address;
        $profile->sex = $request->sex;
        $profile->handphone = $request->handphone;
        $profile->birthplace = $request->birthplace;
        $profile->birthdate = $request->birthdate;

        if ($request->filled('password')) $profile->password = Hash::make($request->password);

        if ($request->hasFile('signature')) {
            $oldFile = $profile->signature;

            if (Storage::exists(config('esisma.signatures') . '/' . $oldFile))
                Storage::delete(config('esisma.signatures') . '/' . $oldFile);

            $theFile = $request->file('signature');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'signature-' . time() . '.' . $ext;
            $theFile->storeAs(config('esisma.signatures'), $fileName);
            $profile->signature = $fileName;
        }

        if ($profile->update())
            return response()->json([
            'success' => true,
            'description' => 'Berhasil mengubah prfile!',
        ], 201);

        return response()->json([
            'success' => false,
            'description' => 'Gagal mengubah prfile!',
        ], 417);
    }

    public function getSign($filename)
    {
        $file = config('esisma.signatures') . '/' . $filename;
        $appFile = 'app/' . $file;
        $mime = \Defr\PhpMimeType\MimeType::get($appFile);
        $headers = [
            'Content-Type' => $mime,
            'content-disposition' => 'attachment'
        ];


        if (Storage::exists($file))
            return response()->download(
            storage_path($appFile),
            $filename,
            $headers
        );

        return response()->json([
            'success' => false,
            'description' => 'File gambar tidak ditemukan.'
        ], 417);
    }
}
