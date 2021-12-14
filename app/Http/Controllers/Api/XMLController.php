<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CreateXmlService;
use App\Http\Services\ReadXmlService;
use PDF;

class XMLController extends Controller
{

    protected $createXmlService;
    protected $readXmlService;
    
    /**
     * Initialize all services.
     */
    public function __construct(CreateXmlService $createXmlService,
                                ReadXmlService $readXmlService)
    {
        $this->createXmlService =  $createXmlService;
        $this->readXmlService = $readXmlService;
    }

    /**
     * Read XML File.
     *
     * @return \Illuminate\Http\Response
     */
    public function readXML()
    {
        try {
            $data = $this->readXmlService->readXML('streha_invoice_03.xml');
            return response()->json($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create XML File.
     *
     * @return \Illuminate\Http\Response
     */
    public function createXML()
    {
        try {
            $data = $this->readXmlService->readXML('streha_invoice_03.xml');
            $this->createXmlService->createXML($data, 'nouveaufichier.xml');
            return response()->json();    
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a PDF Invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function printPDF()
    {
        try {
            // share data to view
            $data = [];

            // Add data 
            $pdf = PDF::loadView('invoice590', $data);
  
            // download PDF file with download method
            return $pdf->download('invoice590.pdf');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
