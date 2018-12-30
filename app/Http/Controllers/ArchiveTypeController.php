<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchiveType;

class ArchiveTypeController extends Controller
{
    public function getList()
    {
        $archiveTypes = ArchiveType::all();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $archiveTypes
        ], 200);
    }

    public function get($id)
    {
        $archiveType = ArchiveType::findOrFail($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $archiveType
        ], 200);
    }

    public function store(Request $request)
    {
        $archiveType = new ArchiveType();
        $archiveType->type = $request->type;
        $archiveType->description = $request->description;

        if ($archiveType->save()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menyimpan data.',
                'data' => $archiveType
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
        $archiveType = ArchiveType::findOrFail($id);
        $archiveType->type = $request->type;
        $archiveType->description = $request->description;

        if ($archiveType->update()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menyimpan data.',
                'data' => $archiveType
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan data.',
            'data' => null
        ], 417);
    }

    public function delete($id)
    {
        $archiveType = ArchiveType::findOrFail($id);

        if ($archiveType->delete()) {
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
