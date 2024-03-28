<?php

namespace App\Controllers;
use \IonAuth\Libraries\IonAuth;
use CodeIgniter\Controller;
use App\Models\M_invoicing;
use SimpleSoftwareIO\QrCode\Generator;
use App\Models\M_curl;

class Invoicing extends BaseController
{
    protected $ionAuth;
    public function __construct()
    {
        $model = new IonAuth();
        $this->ionAuth = $model;
    }

    public function gr_index()
    {
        $data['title'] = 'GR';
        $data['ionAuth'] = $this->ionAuth;
        $data['current_user'] = $this->ionAuth->user()->row();
        $data['css']['header'] = array(
            'datatables/DataTables-2.0.1/css/dataTables.dataTables',
            'datatables/DataTables-2.0.1/css/dataTables.dateTime.min',
            'daterangepicker/daterangepicker',
        );
        $data['js']['footer'] = array(
            'datatables/DataTables-2.0.1/js/dataTables',
            'moment/moment.min',
            'datatables/DataTables-2.0.1/js/dataTables.dateTime.min',
            'daterangepicker/daterangepicker.min',
        );

        // $requestData = json_decode(file_get_contents('php://input'), true);
        // if ($requestData !== null && isset($requestData['startDate']) && isset($requestData['endDate'])) {
        //     // Jika data diterima dengan benar, ambil startDate dan endDate
        //     $formatted_date_low = date('Ymd', strtotime($requestData['startDate']));
        //     $formatted_date_high = date('Ymd', strtotime($requestData['endDate']));
    
        //     // Sekarang Anda dapat menggunakan $startDate dan $endDate sesuai kebutuhan dalam logika bisnis Anda.
        // } else {
        //     // Tangani kasus di mana data tidak diterima dengan benar
        //     $formatted_date_low = date('Ym01');
        //     $formatted_date_high = date('Ymt');
        // }

        // if ($startDate === NULL && $endDate === NULL) {
        //     $formatted_date_low = date('Ym01');
        //     $formatted_date_high = date('Ymt');
        // } else if ($startDate > $endDate){
		// 	$notif['notif_message'] = "Tanggal periode awal lebih dari tanggal periode akhir";
        //     session()->setFlashdata('notif', $notif['notif_message']);
        //     $formatted_date_low = date('Ymd', strtotime($startDate));
        //     $formatted_date_high = date('Ymd', strtotime($endDate));
        // } else {
        //     $formatted_date_low = date('Ymd', strtotime($startDate));
        //     $formatted_date_high = date('Ymd', strtotime($endDate));
        // }

        // if ($this->ionAuth->inGroup('quality')) {
        //     $data['usergroup'] = 'member';
        // }

        // $data['data_sap'] = [];
        // $data['error'] = [];
        // $M_curl = new M_curl();
        // $this->SAP_PARAMS['function'] = 'Z_QC';
        // // Request pertama
        // $this->SAP_PARAMS['params'] = [
        //     'RPT' => 'P_GR_SELECT',
        //     'CNMA' => $data['current_user']->id_vendor,
        //     'P_DATE_LOW' => $formatted_date_low,
        //     'P_DATE_HIGH' => $formatted_date_high
        // ];

        // $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        // if ($sap['success']) {
        //     $data['data_sap'] = (isset($sap['data']['ZGRUSER'])) ? $sap['data']['ZGRUSER'] : [];
        //     echo json_encode($data['data_sap']);
        // }
        // if (isset($sap['message'])) {
        //     $data['error'] = $sap['message'];
        // }
        // $M_invoicing = new M_invoicing();
        // $query = $M_invoicing->getData();
        // $data['data_po'] =  $M_invoicing->getData($startDate, $endDate);
        return $this->_render_page('invoicing/GR/index', $data);
    }


    public function gr_real()
    {
        $data['current_user'] = $this->ionAuth->user()->row();
        $requestData = json_decode(file_get_contents('php://input'), true);

        if ($requestData != NULL) {
            $formatted_date_low = $requestData['startDate'] != "" ? date('Ymd', strtotime($requestData['startDate'])) : NULL;
            $formatted_date_high = $requestData['endDate'] != "" ? date('Ymd', strtotime($requestData['endDate'])) : NULL;
            $po_number = $requestData['poNumber'] != "" ? $requestData['poNumber'] : NULL;
        } else {
            $formatted_date_low = NULL;
            $formatted_date_high = NULL;
            $po_number = NULL;
        }        

        $data['date_low'] = $formatted_date_low;
        $data['date_high'] = $formatted_date_high;
        $data['po_number'] = $po_number;

        $M_curl = new M_curl();
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_SELECT',
            'CNMA' => $data['current_user']->id_vendor,
            'P_DATE_LOW' => $formatted_date_low,
            'P_DATE_HIGH' => $formatted_date_high,
            'P_EBELN' => $po_number,
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        if ($sap['success']) {
            $data['data_sap'] = isset($sap['data']['ZGRUSER']) ? $sap['data']['ZGRUSER'] : [];
        } else {
            $data['error'] = isset($sap['message']) ? $sap['message'] : 'Terjadi kesalahan dalam permintaan SAP.';
        }
        echo json_encode($data); // Keluarkan respons JSON tunggal dari server
    }

    public function gr_approve($gr, $item)
    {
        // $M_invoicing = new M_invoicing();
        // $action = $this->request->getPost('action');
        // $isVerified = ($action == 'verified') ? '1' : '0';

        // $dataToUpdate = [
        //     'is_verified' => $isVerified,
        // ];
        // $result = $M_invoicing->updateGRData($id, $dataToUpdate);
        // if ($result) {
        //     return redirect()->to('invoicing/gr');
        // } else {
        //     echo 'Gagal mengupdate data.';
        // }

        $data['data_sap'] = [];
        $data['ionAuth'] = $this->ionAuth;
        $M_curl = new M_curl();
        $this->SAP_PARAMS['function'] = 'Z_QC';
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_UPDATE_GR',
            'P_BELNR' => $gr,
            'P_EBELP' => $item
        ];
        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        return redirect()->to('invoicing/gr')->with('success', $sap['message']);
    }

    public function reistration_dropbox()
    {
        $data['title'] = 'Registration Dropbox';
        $data['ionAuth'] = $this->ionAuth;
        $data['current_user'] = $this->ionAuth->user()->row();
        $data['css']['header'] = array(
            'datatables/DataTables-2.0.1/css/dataTables.dataTables',
            'datatables/DataTables-2.0.1/css/dataTables.dateTime.min',
            'daterangepicker/daterangepicker',
        );
        $data['js']['footer'] = array(
            'datatables/DataTables-2.0.1/js/dataTables',
            'moment/moment.min',
            'datatables/DataTables-2.0.1/js/dataTables.dateTime.min',
            'daterangepicker/daterangepicker.min',
        );

        return $this->_render_page('invoicing/verify_transaction/index', $data);
    }


    public function status_update($id)
    {
        $data['title'] = 'Invoicing';
        $data['ionAuth'] = $this->ionAuth;
        $M_invoicing = new M_invoicing();
        $data['js']['footer'] = array(
            'bs-custom-file-input/bs-custom-file-input.min',
        );
        $data['gr_data'] = $M_invoicing->getGRbyID($id);
        $data['current_user'] = $this->ionAuth->user()->row();
        return $this->_render_page('invoicing/GR/form', $data);
    }

    public function print_gr()
    {
        $data['title'] = 'Invoicing';
        $data['ionAuth'] = $this->ionAuth;
        $qrcode = new Generator;
        $data['styleRound'] = $qrcode->size(150)->color(0, 0, 0)->backgroundColor(255, 255, 255)->style('round')->generate('https://www.binaryboxtuts.com/');
        $data['current_user'] = $this->ionAuth->user()->row();
        return $this->_render_page('invoicing/GR/printgr', $data);
    }


    public function table_res()
    {
        $M_invoicing = new M_invoicing();
        $data['ionAuth'] = $this->ionAuth;
        $data['data_po'] =  $M_invoicing->getData();
        return view('invoicing/verify_transaction/table',$data);
    }

    public function verify_transaction()
    {
        $data['title'] = 'Verify Transaction';
        $data['ionAuth'] = $this->ionAuth;
        $data['current_user'] = $this->ionAuth->user()->row();
        $data['css']['header'] = array(
            'datatables/DataTables-2.0.1/css/dataTables.dataTables',
            'datatables/DataTables-2.0.1/css/dataTables.dateTime.min',
            'daterangepicker/daterangepicker',
        );
        $data['js']['footer'] = array(
            'datatables/DataTables-2.0.1/js/dataTables',
            'moment/moment.min',
            'datatables/DataTables-2.0.1/js/dataTables.dateTime.min',
            'daterangepicker/daterangepicker.min',
        );

        return $this->_render_page('invoicing/verify_transaction/index', $data);
    }

    public function jsonverify()
    {
        $data['current_user'] = $this->ionAuth->user()->row();
        $requestData = json_decode(file_get_contents('php://input'), true);

        if ($requestData != NULL) {
            $formatted_date_low = $requestData['startDate'] != "" ? date('Ymd', strtotime($requestData['startDate'])) : NULL;
            $formatted_date_high = $requestData['endDate'] != "" ? date('Ymd', strtotime($requestData['endDate'])) : NULL;
            $po_number = $requestData['poNumber'] != "" ? $requestData['poNumber'] : NULL;
        } else {
            $formatted_date_low = NULL;
            $formatted_date_high = NULL;
            $po_number = NULL;
        }        

        $data['date_low'] = $formatted_date_low;
        $data['date_high'] = $formatted_date_high;
        $data['po_number'] = $po_number;

        $M_curl = new M_curl();
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_GR_SELECT',
            'CNMA' => $data['current_user']->id_vendor,
            'P_DATE_LOW' => $formatted_date_low,
            'P_DATE_HIGH' => $formatted_date_high,
            'P_EBELN' => $po_number,
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);
        if ($sap['success']) {
            $data['data_sap'] = isset($sap['data']['ZGRUSER']) ? $sap['data']['ZGRUSER'] : [];
        } else {
            $data['error'] = isset($sap['message']) ? $sap['message'] : 'Terjadi kesalahan dalam permintaan SAP.';
        }
        echo json_encode($data); // Keluarkan respons JSON tunggal dari server
    }
}
