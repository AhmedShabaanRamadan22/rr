<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\PdfTrait;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    use PdfTrait;

    public function test($path,$output = 'I'){
        if(!$this->check_allowed_output_type($output)){
            return "Output Not Allowed";
        }

        return $this->generatePDF($path,'test',$output," "," ");
        // return $this->generatePDF($path,'test',$output);
    }
}
