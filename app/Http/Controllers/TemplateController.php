<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateField;
use Schema;
use Illuminate\Support\Facades\DB;
use Helpers;
use Illuminate\Support\Facades\Storage;


class TemplateController extends Controller
{
    public function list()
    {
        $templates = Template::all();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $templates
        ], 200);
    }

    public function get($id)
    {
        $template = Template::findOrFail($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $template
        ], 200);
    }

    public function fields($id)
    {
        $template = Template::findOrFail($id);

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $template->template_fields
        ], 200);
    }

    public function create(Request $request)
    {
        $template = new Template();
        $template->title = $request->title;
        $template->needs_villager_data = $request->needs_villager_data;
        if ($request->hasFile('template_file')) {
            $theFile = $request->file('template_file');
            $path = config('esisma.templates');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'template-' . time() . '.' . $ext;
            $theFile->storeAs($path, $fileName);
            $template->template_file = $fileName;
        }

        if ($template->save()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dibuat.',
                'data' => $template
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.',
            'data' => null
        ], 417);
    }

    public function update($id, Request $request)
    {
        $template = Template::find($id);
        $template->title = $request->title;
        $template->needs_villager_data = $request->needs_villager_data;
        if ($request->hasFile('template_file')) {
            $theFile = $request->file('template_file');
            $oldFile = $template->template_file;
            $path = config('esisma.templates');
            $oldPath = $path . '/' . $oldFile;
            if (Storage::exists($oldPath)) Storage::delete($oldPath);
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'template-' . time() . '.' . $ext;
            $theFile->storeAs($path, $fileName);
            $template->template_file = $fileName;
        }

        if ($template->update()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil diperbarui.',
                'data' => $template
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal memperbarui.',
            'data' => null
        ], 417);
    }

    public function delete($id)
    {
        $template = Template::find($id);

        $path = config('esisma.templates') . '/' . $template->template_file;
        if (Storage::exists($path)) Storage::delete($path);
        if ($template->delete()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dihapus.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data di database, file erhasil dihapus di storage server'
        ], 417);

    }

    public function addField($id, Request $request)
    {
        $template = Template::findOrFail($id);
        $data = $request->all();

        if ($request->type != 4) unset($data['user_id']);

        if ($template->template_fields()->create($data)) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil dibuat.',
                'data' => $template->template_fields
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal dibuat.',
            'data' => null
        ], 417);
    }

    public function removeField($templateId, $id)
    {
        $templateField = TemplateField::find($id);

        if ($templateField->delete())
            return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.'
        ], 200);

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.'
        ], 417);
    }

    public function getResources()
    {
        $data = [
            'villager_fields' => \App\Models\Villager::getFIelds(),
            'users' => \App\Models\User::getSelectOptions()
        ];

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $data
        ], 200);
    }


}
