<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use \App\Models\Temp;
use \IonAuth\Libraries\IonAuth;
use App\Models\M_curl;

class Dashboard extends BaseController
{
    protected $ionAuth;

    public function __construct()
    {
        $model = new IonAuth();
        $this->ionAuth = $model;
    }

    public function sup_index()
    {
        $data['title'] = 'Dashboard';
        $data['ionAuth'] = $this->ionAuth;
        $data['js']['footer'] = array(
            'datatables/jquery.dataTables.min',
            'datatables-bs4/js/dataTables.bootstrap4.min',
            'datatables-responsive/js/dataTables.responsive.min',
            'datatables-responsive/js/responsive.bootstrap4.min',
            'datatables/DataTables-2.0.1/js/dataTables',
            'moment/moment.min',
            'datatables/DataTables-2.0.1/js/dataTables.dateTime.min',
            'daterangepicker/daterangepicker.min',
        );

        $data['css']['header'] = array(
            'datatables/DataTables-2.0.1/css/dataTables.dataTables',
            'datatables/DataTables-2.0.1/css/dataTables.dateTime.min',
            'daterangepicker/daterangepicker',
        );

        $data['current_user'] = $this->ionAuth->user()->row();
        return $this->_render_page('dashboard/supplier/index', $data);
    }

    public function dash_real()
    {
        $data['current_user'] = $this->ionAuth->user()->row();
        $requestData = json_decode(file_get_contents('php://input'), true);   

        // $start_date = date("Y-01-01");
        // $end_date = date("Y-m-d");

        $start_date = date("2016-01-01");
        $end_date = date("2016-01-03");

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        if ($requestData['indikasi'] == 'UNVERIFIED'){
            $data['table_header'] = 'Total GR Unverified';
        } elseif ($requestData['indikasi'] == 'RECEIVED'){
            $data['table_header'] = 'Total Received';
        } else {
            $data['table_header'] = 'Total All Document';
        }

        // Format tanggal menjadi sesuai dengan kebutuhan
        $formatted_date_low = date("Ymd", strtotime($start_date));
        $formatted_date_high = date("Ymd", strtotime($end_date));

        $M_curl = new M_curl();
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_DASH',
            'CNMA' => $data['current_user']->id_vendor,
            'P_DATE_LOW' => $formatted_date_low,
            'P_DATE_HIGH' => $formatted_date_high,
            'INDEX' => $requestData['indikasi'],
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        if ($sap['success']) {
            $data['data_sap'] = isset($sap['data']['ZGRUSER']) ? $sap['data']['ZGRUSER'] : [];
            $data['current_user_company_name'] = $data['current_user']->company_name;
        } else {
            $data['error'] = isset($sap['message']) ? $sap['message'] : 'Terjadi kesalahan dalam permintaan SAP.';
        }
        echo json_encode($data); // Keluarkan respons JSON tunggal dari server
    }


    public function card_value()
    {
        $data['current_user'] = $this->ionAuth->user()->row();
        $requestData = json_decode(file_get_contents('php://input'), true);   

        // $start_date = date("Y-01-01");
        // $end_date = date("Y-m-d");

        $start_date = date("2016-01-01");
        $end_date = date("2016-01-03");

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Format tanggal menjadi sesuai dengan kebutuhan
        $formatted_date_low = date("Ymd", strtotime($start_date));
        $formatted_date_high = date("Ymd", strtotime($end_date));

        $M_curl = new M_curl();
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_DASH',
            'CNMA' => $data['current_user']->id_vendor,
            'P_DATE_LOW' => $formatted_date_low,
            'P_DATE_HIGH' => $formatted_date_high,
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        if ($sap['success']) {
            $data['data_sap'] = isset($sap['data']['ZGRUSER']) ? $sap['data']['ZGRUSER'] : [];
            $data['current_user_company_name'] = $data['current_user']->company_name;
            $jumlah_GR = 0;
            $jumlah_non_GR = 0;
            foreach ($data['data_sap'] as $item) {
                if ($item['VBELN_ST'] == 'GR') {
                    $jumlah_GR++;
                } else {
                    $jumlah_non_GR++;
                }
            }
            // Menambahkan jumlah data ke dalam array data
            $data['jumlah_ALL'] = $data['data_sap'];
            $data['jumlah_GR'] = $jumlah_GR;
            $data['jumlah_non_GR'] = $jumlah_non_GR;

        } else {
            $data['error'] = isset($sap['message']) ? $sap['message'] : 'Terjadi kesalahan dalam permintaan SAP.';
            $data['jumlah_ALL'] = [];
            $data['jumlah_GR'] = 0;
            $data['jumlah_non_GR'] = 0;
        }
        //Respon JSON//
        echo json_encode($data); // Keluarkan respons JSON tunggal dari server
    }
}
