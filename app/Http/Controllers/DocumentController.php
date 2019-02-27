<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Letter;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('manageDocs', [
            'except' => [
                'get',
                'responseFile',
                'store',
                'getUserDocuments',
                'getByArchive'
            ]
        ]);
        $this->middleware('document', [
            'only' => [
                'get',
                'responseFile'
            ]
        ]);
    }

    public function index()
    {
        $documents = Document::with('archive')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $documents
        ], 200);
    }

    public function getUserDocuments()
    {
        $userId = app('auth')->user()->id;

        $documents = Document::with('archive')->with('uploader')->where('uploader_id', $userId)->orWhere('public', true)->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $documents
        ], 200);
    }

    public function getByArchive($archiveId)
    {
        $documents = Document::where('archive_id', $archiveId)->with('archive')->with('uploader')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $documents
        ], 200);
    }

    public function get($id)
    {
        $document = Document::with('archive')->with('uploader')
            ->with('letter')->find($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $document
        ], 200);
    }

    public function store(Request $request)
    {
        $document = new Document();
        $document->title = $request->title;
        $document->date = $request->date;
        $document->description = $request->description;
        $theFile = $request->file('file');
        $ext = $theFile->getClientOriginalExtension();
        $fileName = 'document-' . time() . '.' . $ext;
        $document->uploader_id = app('auth')->user()->id;
        $document->file_type = $request->file_type;
        $document->path = $fileName;
        $document->public = $request->public;
        $document->archive_id = $request->filled('archive_id') ? $request->archive_id : null;

        if ($document->save()) {

            if ($request->filled('letter_id')) {
                $letter = Letter::find($request->letter_id);
                $letter->document()->associate($document);
                $letter->save();
                $theFile->storeAs($document->getPathFile(), $fileName);
            } else {
                $theFile->storeAs($document->getPathFile(), $fileName);
            }

            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }


    public function update($id, Request $request)
    {
        if (!app('auth')->user()->documents()->find($id))
            return response()->json([
            'success' => true,
            'description' => 'Tidak memiliki akses.'
        ], 403);

        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->date = $request->date;
        $document->public = $request->public;
        $document->description = $request->description;
        $document->archive_id = $request->filled('archive_id') ? $request->archive_id : null;

        if ($request->hasFile('file')) {
            if (Storage::exists($document->path_file)) {
                Storage::delete($document->path_file);
            }
            $document->path = '';
            $document->uploader_id = app('auth')->user()->id;
            $theFile = $request->file('file');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'document-' . time() . '.' . $ext;
            $theFile->storeAs($document->path_file, $fileName);
            $document->path = $fileName;
            $document->file_type = $request->file_type;
        }

        if ($document->update()) {
            if ($request->filled('letter_id')) {
                $letter = Letter::find($request->letter_id);
                $letter->document()->associate($document);
                $letter->save();
            }

            return response()->json([
                'success' => true,
                'description' => 'Berhasil disimpan',
                'data' => $document
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.'
        ], 417);
    }

    public function delete($id)
    {
        if (!app('auth')->user()->documents()->find($id)
            && !app('auth')->user()->role->has('super_user'))
            return response()->json([
            'success' => true,
            'description' => 'Tidak memiliki akses.'
        ], 403);

        $document = Document::find($id);
        $path = $document->path_file;

        if ($document->delete()) {
            if (Storage::exists($path)) Storage::delete($path);
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dihapus.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.'
        ], 417);
    }

    public function responseFile($path)
    {
        $document = Document::where('path', $path)->first();
        $headers = [
            'Content-Type' => $document->file_type,
            'content-disposition' => 'attachment'
        ];

        return response()->download(
            storage_path('app/' . $document->path_file),
            $path,
            $headers
        );
    }
}
