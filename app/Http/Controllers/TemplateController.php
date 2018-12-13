<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateField;
use Schema;
use Illuminate\Support\Facades\DB;
use Helpers;


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
        if($request->file('template_file')){
            $theFile = $request->file('template_file');
            $path = config('esisma.templates');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'template-'.time().'.'.$ext;
            $theFile->storeAs($path, $fileName);
            $template->template_file = $fileName;
        }

        if($template->save()){
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

    public function addField($id, Request $request)
    {
        $template = Template::findOrFail($id);
        if($template->template_fields()->create($request->all())){
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

    public function villagerColumns()
    {
        $columns = [
            '"name"',
            '"birthplace"',
            '"birthdate"',
            '"job"',
            '"religion"',
            '"tribe"',
            '"NIK"',
            '"status"',
            '"address"'
        ];
        return Helpers::showFullColumn('villagers', $columns);
    }
}
