<?php

namespace App\Controllers;

use TCPDF;

class Pdf extends BaseController
{
    public function index()
    {
        // Buat objek TCPDF
        $pdf = new TCPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);

        // Set properti dokumen
        $pdf->SetCreator('My PDF Creator');
        $pdf->SetTitle('Hello World');
        $pdf->SetHeaderData('', 0, 'Hello World', 'PDF Header', array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // Tambahkan halaman
        $pdf->AddPage();

        // Tambahkan konten ke halaman
        $pdf->SetFont('times', 'I', 12);
        $pdf->Cell(0, 10, 'Hello World', 0, 1, 'C');
        $this->response->setContentType('application/pdf');
        // Output PDF ke browser
        $pdf->Output('hello_world.pdf', 'I');
    }
}