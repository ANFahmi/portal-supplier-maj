<?php

namespace App\Controllers;
use App\Models\ExcelModel;
use App\Models\M_curl;

class Temp extends BaseController
{
    public function upload()
    {
        $excelModel = new ExcelModel();

        // Ganti 'input_file.xlsx' dengan nama file Excel yang ingin Anda proses
        // Ganti 'output_file.xlsx' dengan nama file Excel yang akan disimpan hasilnya
        $inputFilePath = WRITEPATH . 'uploads/temp.xlsx';
        $outputFilePath = WRITEPATH . 'uploads/output_file.xlsx';

        $excelModel->hashAndWritePasswords($inputFilePath, $outputFilePath);

        return "Proses selesai. Hasil disimpan di: $outputFilePath";
    }

    public function temp()
	{
        $this->ionAuth    = new \IonAuth\Libraries\IonAuth();
        $user = $this->ionAuth->user()->row();
        echo $user->email;
    }

    public function getPart()
    {
        $output['data'] = [];
        $M_curl = new M_curl();
        $date_low = '01.01.2016';
        $date_high = '03.01.2016';
        $formatted_date_low = date('Ymd', strtotime($date_low));
        $formatted_date_high = date('Ymd', strtotime($date_high));
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_SELECT',
            'CNMA' => '0000100444',
            'P_DATE_LOW' => $formatted_date_low,
            'P_DATE_HIGH' => $formatted_date_high,
        ];
        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        $output['data'] = $sap;
        // Request kedua
        // $this->SAP_PARAMS['params'] = [
        //     'RPT' => 'P_MATNR_HALB',
        // ];
        // $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        var_dump($sap);


        // $M_curl = new M_curl;
        // $M_curl->temporary();
    }

    public function postDataToSap()
    {
        $M_curl = new M_curl();
        $this->SAP_PARAMS['data'] = array(
            'PTAG'        => 'PTAG',
            'PDATE'       => 'PDATE',
            'PSHIFT'      => 'PSHIFT',
            'PMATNR'      => 'PMATNR',
            'PQTY'        => 'PQTY',
            'PKET'        => 'PKET',
            'PUSER_INPUT' => 'PUSER_INPUT',
            'PDATE_INPUT' => 'PDATE_INPUT',
        );
        $this->SAP_PARAMS['function'] = 'Z_QC';
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_MATNR_FERT',
        ];
        // $response = $M_curl->insertDataToSAP($this->SAP_PARAMS);
        print_r(json_encode($this->SAP_PARAMS));
    }
}
