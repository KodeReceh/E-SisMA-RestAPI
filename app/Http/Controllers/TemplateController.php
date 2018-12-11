<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    public function create(Request $request)
    {
        $template = new Template();

        if($template->create($request->all())){
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
}
