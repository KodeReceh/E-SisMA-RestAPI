<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;

class ArchiveController extends Controller
{
    public function getList()
    {
        $userRoleId = app('auth')->user()->id;
        $archives = Archive::where('role_id', $userRoleId)->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $archives
        ], 200);
        
    }

    public function get($id)
    {
        $archive = Archive::findOrFail($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $archive
        ], 200);
    }

    public function store(Request $request)
    {
        $userRoleId = app('auth')->user()->id;
        $archive = new Archive();
        $archive->title = $request->title;
        $archive->role_id = $userRoleId;
        $archive->date = $request->date;
        $archive->archive_type_id = $request->archive_type_id;
        $archive->description = $request->description;        

        if($archive->save()){
            return response()->json([
                'success' => true,
                'description' => 'Sukses menyimpan data.',
                'data' => $archive
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan data.',
            'data' => null
        ], 417);
    }

    public function update($id, Request $request)
    {
        $archive = Archive::findOrFail($id);
        $archive->title = $request->title;
        $archive->date = $request->date;
        $archive->archive_type_id = $request->archive_type_id;
        $archive->description = $request->description;  

        if($archive->update()){
            return response()->json([
                'success' => true,
                'description' => 'Sukses menyimpan data.',
                'data' => $archive
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'gagal menyimpan data.',
            'data' => null
        ], 417);
    }

    public function delete($id)
    {
        $archive = Archive::findOrFail($id);

        if($archive->delete()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menghapus data.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.'
        ], 417);
    }
}
