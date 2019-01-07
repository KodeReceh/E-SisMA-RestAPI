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
use Helpers;
use App\Models\Letter;
use App\Models\OutcomingLetter;
use App\Models\Document;

class LetterTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('sign', [
            'only' => [
                'sign',
                'unsign'
            ]
        ]);
        $this->middleware('check:atur_draft_surat_keluar', [
            'except' => [
                'sign',
                'unsign',
                'get'
            ]
        ]);
        $this->middleware('getDraft', [
            'only' => [
                'get'
            ]
        ]);
    }

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
        $data = [];

        foreach ($text as $key => $field) {
            $name = $field->name;
            $data[$name] = $request->$name;
        }

        $images = $template->template_fields()->where('type', 2)->get();

        foreach ($images as $key => $image) {
            if ($request->hasFile($image->name)) {
                $theFile = $request->file($image->name);
                $ext = $theFile->getClientOriginalExtension();
                $fileName = $image->name . '-' . time() . '.' . $ext;
                $theFile->storeAs(config('esisma.raw_images'), $fileName);
                $data[$image->name] = $fileName;
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
            $mime = \Defr\PhpMimeType\MimeType::get($letter->generated_file);

            return $this->responseFile($path, $letter->letter_name, $ext, $size, $mime);
        } else {
            $generated = $this->generateDoc($letter);
            if ($generated && is_bool($generated)) {
                $path = storage_path('app/' . config('esisma.generated_docs') . '/' . $letter->generated_file);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $size = filesize($path);
                $mime = \Defr\PhpMimeType\MimeType::get($letter->generated_file);

                return $this->responseFile($path, $letter->letter_name, $ext, $size, $mime);
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
        $numberField = config('esisma.letter_number_field_alias');
        $letterDateField = config('esisma.letter_date_field_alias');
        $letterDate = \Carbon\Carbon::now();
        $letterNumber = Helpers::generateLetterNumber($template->letter_code_id, $template->title);
        $templateFile->setValue($numberField, $letterNumber);
        $templateFile->setValue($letterDateField, Helpers::translateDate($letterDate));
        $outcomingLetter = new Letter();
        $outcomingLetter->number = $letterNumber;
        $outcomingLetter->date = $letterDate->format('Y-m-d');
        $outcomingLetter->subject = $letter->letter_name;
        $outcomingLetter->letter_code_id = $template->letter_code_id;
        $outcomingLetter->save();
        $outcomingLetter->outcoming_letter()->save(new OutcomingLetter([
            'recipient' => $template->needs_villager_data ? 'Penduduk' : 'Lainnya',
            'ordinal' => OutcomingLetter::getOrdinal($letterDate->format('Y'))
        ]));

        foreach ($template->template_fields as $key => $field) {
            $name = $field->name;

            switch ($field->type) {
                case 1:
                    $templateFile->setValue($name, $data->$name);
                    break;

                case 2:
                    $templateFile->setImageValue($name, storage_path('app/' . config('esisma.raw_images') . '/' . $data->$name));
                    break;
                case 3:
                    $field = config('esisma.villager_fields')[$name];
                    $templateFile->setValue($name, $letter->villager->$field);
                    break;

                case 4:
                    $signature = $field->user->signature;
                    $userName = $field->user->name;
                    $IDNumber = $field->user->employee_id_number;
                    $hasSigned = $letter->hasUserSignedIt($field->user->id);

                    $templateFile->setImageValue(
                        config('esisma.signature_field_prefix') . $name,
                        storage_path('app/' . config('esisma.signatures') . '/' . ($hasSigned ? $signature : config('esisma.empty_sign_file')))
                    );
                    $templateFile->setValue(config('esisma.signer_name_field_prefix') . $name, $userName);
                    $templateFile->setValue(config('esisma.signer_ID_field_prefix') . $name, $IDNumber);
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

            if (Storage::copy(config('esisma.generated_docs') . '/' . $thisIsTheFileName, config('esisma.dokumen.surat.keluar') . '/' . $thisIsTheFileName)) {
                $document = new Document();
                $document->title = $letter->letter_name;
                $document->path = $thisIsTheFileName;
                $document->uploader_id = app('auth')->user()->id;
                $document->file_type = \Defr\PhpMimeType\MimeType::get($thisIsTheFileName);
                $document->date = $outcomingLetter->date;
                $document->save();
                $outcomingLetter->document()->associate($document);
                $outcomingLetter->save();
            }

            $letter->generated_file = $thisIsTheFileName;
            $letter->save();
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    protected function responseFile($path, $name, $extension, $size, $mime)
    {
        $headers = [
            'Content-Type' => $mime,
            'Content-disposition' => 'attachment; filename=' . $name . '.' . $extension,
            'Content-length' => $size,
            'Connection' => 'Keep-Alive',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
        ];

        return new BinaryFileResponse($path, 200, $headers);
    }

    public function get($id)
    {
        $letter = LetterTemplate::find($id);
        // dd($letter->data);
        // $letter->data = json_decode($letter->data);
        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $letter
        ], 200);
    }

    public function sign($id)
    {
        $userId = app('auth')->user()->id;
        $letter = LetterTemplate::find($id);

        if ($letter->signLetter($userId)) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil menandatangani surat.',
                'data' => $letter
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menandatangani surat.',
            'data' => $letter
        ], 417);
    }

    public function unsign($id)
    {
        $userId = app('auth')->user()->id;
        $letter = LetterTemplate::find($id);

        if ($letter->unsignLetter($userId)) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil membatalkan tanda tangan surat.',
                'data' => $letter
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal membatalkan tanda tangan surat.',
            'data' => $letter
        ], 417);
    }

    public function delete($id)
    {
        $letter = LetterTemplate::find($id);

        if ($letter->generated_file && Storage::exists(config('esisma.generated_docs') . '/' . $letter->generated_file)) {
            Storage::delete(config('esisma.generated_docs') . '/' . $letter->generated_file);
        }

        $images = $letter->template->template_fields()->where('type', 2)->get();
        $data = json_decode($letter->data);

        foreach ($images as $key => $image) {
            $name = $image->name;
            if (Storage::exists(config('esisma.raw_images') . '/' . $data->$name))
                Storage::delete(config('esisma.raw_images') . '/' . $data->$name);
        }

        $letter->delete();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus.'
        ], 200);
    }

    public function deleteGeneratedFile($id)
    {
        $letter = LetterTemplate::find($id);
        if ($letter->deleteGeneratedFile()) {
            return response()->json([
                'succees' => true,
                'description' => 'Berhasil menghapus data.',
                'data' => $letter
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal menghapus data.',
            'data' => $letter
        ], 417);
    }
}
