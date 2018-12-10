<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class LetterTemplateController extends Controller
{
    public function generateFromTemplate()
    {
        $template = new TemplateProcessor(storage_path('app/templates/contoh.docx'));
        $template->setValue('nama', 'Muhammad Fauzi');
        $template->setValue('apa', 'Ini bold kah?');
        $template->setImageValue('gambar', storage_path('app/templates/gambar.jpg'));
        $template->saveAs(storage_path('app/templates/inijadinya.docx'));
    }
}
