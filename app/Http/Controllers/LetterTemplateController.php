<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use App\Models\Template;
use App\Models\Villager;
use App\Models\LetterTemplate;

class LetterTemplateController extends Controller
{
    public function generateFromTemplate($id, Request $request)
    {
        $template = Template::findOrFail($id);
        
        foreach($template->template_fields as $field){
            switch ($field->type) {
                case 0:
                    $templateData[$field->name] = $request->$field->name;
                    break;

                case 1:
                    if($request->file($field->name)){
                        $path = config('esisma.template_data_image');
                        $theFile = $request->file($field->name);
                        $ext = $theFile->getClientOriginalExtension();
                        $fileName = $field->id.'-'.time().'.'.$ext;
                        $theFile->storeAs($path, $fileName);
                        $templateData[$field->name] = $fileName;
                    }
                    break;

                case 2:
                    $villager_id = ($request->$field->name)[0];
                    $villager_field = $request->$field->name[1];
                    $villager = Villager::find($villager_id);
                    $templateData[$field->name] = $villager->$villager_field;

                default:
                    break;
            }
        }

        $jsonDataTemplate = json_encode($templateData);
        $letterTemplate = new LetterTemplate();
        $letterTemplate->template_id = $id;
        $letterTemplate->data = $jsonDataTemplate;
        if($letterTemplate->save()){
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menyimpan.',
                'data' => $letterTemplate
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan.',
            'data' => null
        ], 417);

        // $templatePath = config('esisma.templates');
        // $templateFile = new TemplateProcessor(storage_path('app/'.$templatePath.'/'.$template->template_file));
        // $templateData = [];
        // $template->setValue('nama', 'Muhammad Fauzi');
        // $template->setValue('apa', 'Ini bold kah?');
        // $template->setImageValue('gambar', storage_path('app/templates/gambar.jpg'));
        // $template->saveAs(storage_path('app/templates/inijadinya.docx'));
    }

    public function testQRCode()
    {
        $data = "I know I am awesome! ありがごう！";
        return '<img src="'.(new QRCode)->render($data).'" />';
    }
}
