<?php

namespace App\Http\Controllers\ProjectAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class WordController extends Controller
{
    public function getWord()
    {
        $phpword = new PhpWord();
        $section = $phpword->addSection();
        $fontStyle = [
            'name' => '宋体',
            'size' => 12,
            'color' => '#ff6600',
            'bold' => true
        ];
        $textrun = $section->addTextRun();
        $textrun->addText('你好，这是生成的Word文档。 ', $fontStyle);
        $filename = 'test.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
        $xmlWriter->save("php://output");

    }
}
