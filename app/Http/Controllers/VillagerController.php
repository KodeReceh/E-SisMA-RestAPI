<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VillagerController extends Controller
{
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

        if($request->hasFile('photo')){
            $theFile = $request->file('photo');
            $filename = 'villager-'.$villager->id;
            $extension = $theFile->getClientOriginalExtension();
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
        $villager->update();

        if($request->hasFile('photo')){
            $theFile = $request->file('photo');
            $filename = 'villager-'.$villager->id;
            $extension = $theFile->getClientOriginalExtension();
            $oldFile = $villager->photo;
            if(Storage::exists(config('esisma/villager_photos').'/'.$oldFile)) Storage::delete(config('esisma/villager_photos').'/'.$oldFile);
            $theFile->storeAs(config('esisma.villager_photos'), $filename);
            $villager->photo = $filename;
            $villager->update();
        }

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengubah data.',
            'data' => $villager
        ], 201);
    }

    public function delete($id)
    {
        $villager = Villager::findOrFail($id);
        if(Storage::exists(config('esisma/villager_photos').'/'.$oldFile)) Storage::delete(config('esisma/villager_photos').'/'.$oldFile);
        $villager->delete();
        
        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.'
        ], 200);
    }
}
