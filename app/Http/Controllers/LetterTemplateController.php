<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;

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

    public function testQRCode()
    {
        $data = "I know I am awesome! ありがごう！";
        return '<img src="'.(new QRCode)->render($data).'" />';
    }
}
