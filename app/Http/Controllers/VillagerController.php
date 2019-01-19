<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VillagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('check:atur_penduduk', [
            'except' => [
                'all',
                'getFields',
                'get'
            ]
        ]);
    }

    public function all()
    {
        $villagers = Villager::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $villagers
        ], 200);
    }

    public function getFields()
    {
        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => Villager::getFields()
        ], 200);
    }

    public function get($id)
    {
        $villager = Villager::findOrFail($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data',
            'data' => $villager
        ]);
    }

    public function store(Request $request)
    {
        $villager = new Villager();
        $villager->name = $request->name;
        $villager->NIK = $request->NIK;
        $villager->birthplace = $request->birthplace;
        $villager->birthdate = $request->birthdate;
        $villager->sex = $request->sex;
        $villager->job = $request->job;
        $villager->religion = $request->religion;
        $villager->tribe = $request->tribe;
        $villager->status = $request->status;
        $villager->address = $request->address;
        $villager->save();

        if ($request->hasFile('photo')) {
            $theFile = $request->file('photo');
            $extension = $theFile->getClientOriginalExtension();
            $filename = 'villager-' . $villager->id . '.' . $extension;
            $theFile->storeAs(config('esisma.villager_photos'), $filename);
            $villager->photo = $filename;
            $villager->save();
        }

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menyimpan data.',
            'data' => $villager
        ], 201);
    }

    public function update($id, Request $request)
    {
        $villager = Villager::findOrFail($id);
        $villager->name = $request->name;
        $villager->NIK = $request->NIK;
        $villager->birthplace = $request->birthplace;
        $villager->birthdate = $request->birthdate;
        $villager->sex = $request->sex;
        $villager->job = $request->job;
        $villager->religion = $request->religion;
        $villager->tribe = $request->tribe;
        $villager->status = $request->status;
        $villager->address = $request->address;

        if ($request->hasFile('photo')) {
            $theFile = $request->file('photo');
            $extension = $theFile->getClientOriginalExtension();
            $filename = 'villager-' . $villager->id . '.' . $extension;
            if (Storage::exists(config('esisma.villager_photos') . '/' . $villager->photo))
                Storage::delete(config('esisma.villager_photos') . '/' . $villager->photo);
            $theFile->storeAs(config('esisma.villager_photos'), $filename);
            $villager->photo = $filename;
        }
        $villager->update();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengubah data.',
            'data' => $villager
        ], 201);
    }

    public function delete($id)
    {
        $villager = Villager::findOrFail($id);
        if (Storage::exists(config('esisma/villager_photos') . '/' . $villager->photo)) Storage::delete(config('esisma/villager_photos') . '/' . $villager->photo);
        $villager->delete();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.'
        ], 200);
    }

    public function getPic($filename)
    {
        $file = config('esisma.villager_photos') . '/' . $filename;
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
