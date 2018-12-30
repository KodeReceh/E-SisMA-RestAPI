<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use App\Models\Template;
use App\Models\Villager;
use App\Models\LetterTemplate;
use App\Models\User;
use App\Models\TemplateField;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LetterTemplateController extends Controller
{
    public function saveFieldData($id, Request $request)
    {
        $template = Template::findOrFail($id);
        $templateData = [];

        foreach ($template->template_fields as $field) {
            $name = $field->name;
            switch ($field->type) {
                case 1:
                    $templateData[$name] = $request->$name;
                    break;

                case 2:
                    if ($request->file($name)) {
                        $path = config('esisma.template_data_image');
                        $theFile = $request->file($name);
                        $ext = $theFile->getClientOriginalExtension();
                        $fileName = $field->id . '-' . time() . '.' . $ext;
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
        if ($letterTemplate->save()) {
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
        return '<img src="' . (new QRCode)->render($data) . '" />';
    }

    public function getFields($id)
    {
        $template = Template::findOrFail($id);

        $text = $template->template_fields()->where('type', 1)->get();
        $image = $template->template_fields()->where('type', 2)->get();
        $villagers = [];
        if ($template->needs_villager_data)
            $villagers = Villager::orderBy('name')->get();

        $data = [
            'villagers' => $villagers,
            'text' => $text,
            'image' => $image,
            'template' => $template
        ];

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $data
        ], 200);

    }

    public function storeFieldData($id, Request $request)
    {
        $template = Template::find($id);
        $text = $template->template_fields()->where('type', 1)->get();
        $image = $template->template_fields()->where('type', 2)->get();
        return $request;
        $data = [];
        foreach ($text as $key => $field) {
            $name = $field->name;
            $data[$name] = $request->$name;
        }

        foreach ($image as $key => $gambar) {
            $name = $gambar->name;
            if ($request->hasFile($name)) {
                $theFile = $request->file($name);
                $ext = $theFile->getClientOriginalExtension();
                $fileName = 'raw-image-' . time() . '.' . $ext;
                $theFile->storeAs(config('esisma.raw_images'), $fileName);
                $data[$name] = $fileName;
            }
        }
        $letter = new LetterTemplate();
        $letter->template_id = $id;
        $letter->status = 0;
        $letter->letter_name = $request->letter_name;
        if ($template->needs_villager_data)
            $letter->villager_id = $request->villager_id;
        $letter->data = json_encode($data);

        if ($letter->save()) {
            return response()->json([
                'success' => false,
                'description' => 'Gagal menyimpan data',
                'data' => $letter
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menyimpan data',
            'data' => null
        ], 417);
    }

    public function getList()
    {
        $letterTemplates = LetterTemplate::all();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $letterTemplates
        ], 200);
    }

    public function download($id)
    {
        $letter = LetterTemplate::find($id);

        if ($letter->generated_file && Storage::exists(config('esisma.generated_docs') . '/' . $letter->generated_file)) {
            $path = storage_path('app/' . config('esisma.generated_docs') . '/' . $letter->generated_file);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $size = filesize($path);
            $mime = mime_content_type($path);

            return $this->responseFile($path, $ext, $size, $mime);
        } else {
            $generated = $this->generateDoc($letter);
            if ($generated && is_bool($generated)) {
                $path = storage_path('app/' . config('esisma.generated_docs') . '/' . $letter->generated_file);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $size = filesize($path);
                $mime = mime_content_type($path);

                return $this->responseFile($path, $ext, $size, $mime);
            }

            return response()->json([
                'success' => false,
                'description' => $generated->getMessage(),
            ], 417);
        }

    }

    protected function generateDoc(LetterTemplate $letter)
    {
        $template = $letter->template;
        $data = json_decode($letter->data);
        $templatePath = config('esisma.templates');
        $templateFile = new TemplateProcessor(storage_path('app/' . $templatePath . '/' . $template->template_file));
        $extensionDoc = pathinfo($templatePath . '/' . $template->template_file, PATHINFO_EXTENSION);

        foreach ($template->template_fields as $key => $field) {
            $name = $field->name;
            switch ($field->type) {
                case 1:
                    $templateFile->setValue($name, $data->$name);
                    break;

                case 2:
                    $templateFile->setImageValue($name, storage_path('app/' . config('esisma.raw_images') . '/' . $data->$name));

                case 3:
                    break;

                case 4:
                    break;

                default:
                    break;
            }
        }

        try {
            if (!file_exists(storage_path('app/generated_docs'))) {
                mkdir(storage_path('app/generated_docs'), 0755, true);
            }
            $thisIsTheFileName = $template->id . '-' . $letter->id . '-' . time() . '.' . $extensionDoc;
            $templateFile->saveAs(storage_path('app/' . config('esisma.generated_docs') . '/' . $thisIsTheFileName));
            $letter->generated_file = $thisIsTheFileName;
            $letter->save();
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    protected function responseFile($path, $extension, $size, $mime)
    {
        $contentType = $extension == 'doc' ?
            'application/msword' :
            $extension == 'docx' ?
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' :
            '';

        $headers = [
            'Content-Type' => $mime,
            'Content-Transfer-Encoding' => 'Binary',
            'Content-disposition' => 'attachment',
            'Content-length' => $size,
            'Connection' => 'Keep-Alive'
        ];

        return new BinaryFileResponse($path, 200, $headers);
    }
}
