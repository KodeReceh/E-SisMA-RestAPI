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
    public function saveFieldData($id, Request $request)
    {
        $template = Template::findOrFail($id);
        $templateData = [];

        foreach($template->template_fields as $field){
            $name = $field->name;
            switch ($field->type) {
                case 1:
                    $templateData[$name] = $request->$name;
                    break;

                case 2:
                    if($request->file($name)){
                        $path = config('esisma.template_data_image');
                        $theFile = $request->file($name);
                        $ext = $theFile->getClientOriginalExtension();
                        $fileName = $field->id.'-'.time().'.'.$ext;
                        $theFile->storeAs($path, $fileName);
                        $templateData[$name] = $fileName;
                    }
                    break;

                case 3:
                    $villager_id = $request->$name[0];
                    $villager_field = $request->$name[1];
                    $villager = Villager::find($villager_id);
                    $templateData[$name] = $villager->$villager_field;

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
    }

    public function testQRCode()
    {
        $data = "I know I am awesome! ありがごう！";
        return '<img src="'.(new QRCode)->render($data).'" />';
    }

    public function generateDoc($template, $id)
    {
        $template = Template::findOrFail($template);
        $letterTemplate = LetterTemplate::findOrFail($id);
        $data = json_decode($letterTemplate->data);
        $templatePath = config('esisma.templates');
        $templateFile = new TemplateProcessor(storage_path('app/'.$templatePath.'/'.$template->template_file));
        $extensionDoc = pathinfo($templatePath.'/'.$template->template_file, PATHINFO_EXTENSION);
        
        foreach ($template->template_fields as $key => $field) {
            $name = $field->name;
            switch ($field->type) {
                case 1:
                    $templateFile->setValue($name, $data->$name);
                    break;

                case 2:
                    $imgPath = config('esisma.template_data_image');
                    $templateFile->setImageValue($name, storage_path('app/'.$imgPath.'/'.$data->$name));

                case 3:
                    break;

                case 4:
                    break;
                
                default:
                    break;
            }
        }
        
        try {
            $templateFile->saveAs(storage_path('app/generated_docs/'.$template->id.'-'.$letterTemplate->id.'-'.time().'.'.$extensionDoc));   
            return response()->json([
                'success' => true,
                'description' => 'Berhasil membuat dokumen.',
                'data' => [
                    'path' => storage_path('app/generated_docs/'.$template->id.'-'.$letterTemplate->id.'-'.time().'.'.$extensionDoc)
                ]
                ], 201);         
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'descripton' => $th->getMessage(),
                'data' => null
            ], 417);
        }
        
    }
}
