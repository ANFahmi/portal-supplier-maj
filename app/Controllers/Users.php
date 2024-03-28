<?php

namespace App\Controllers;

use \IonAuth\Libraries\IonAuth;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\M_user;
use App\Models\M_auth;
use CodeIgniter\Files\File;
use App\Models\M_curl;

class Users extends BaseController
{
    protected $ionAuth;
    public function __construct()
    {
        $model = new IonAuth();
        $this->ionAuth = $model;
    }

    public function index()
    {
        helper('form', 'url');
        $data['ionAuth'] = $this->ionAuth;
        $data['title'] = 'Profile';
        $data['current_user'] = $this->ionAuth->user()->row();
        return $this->_render_page('user/profile/profile_view', $data);
    }

    public function update()
    {

        helper('form');
        $M_auth = new M_auth();
        $data['ionAuth'] = $this->ionAuth;
        $data['title'] = 'Edit';
        $data['current_user'] = $this->ionAuth->user()->row();
        $data['validation'] =  \Config\Services::validation();
        return $this->_render_page('user/profile/profile_edit', $data);
    }

    public function stored($id)
    {
        helper('form');
        $M_auth = new M_auth();
        $data['ionAuth'] = $this->ionAuth;
        $user = $M_auth->find($id);
        if(empty($user)){
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('users');
        }

        $rules = [
            'company_title' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'Company Title must be filled',
                    'min_length' => 'Company Title must have at least 2 Characters'
                ]
            ],
            'company_name' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Company Name must be filled',
                    'min_length' => 'Company Name must have at least 5 Characters'
                ]
            ],
            'npwp_number' => [
                'rules' => 'required|regex_match[/^\d{15}$/]',
                'errors' => [
                    'required' => 'Company Name must be filled',
                    'regex_match' => 'field does not match the regular expression'
                ]
            ],
            'abbreviated_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Abbreviated Name must be filled',
                ]
            ],
            'supplier_abbreviated' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Supplier Abbreviated must be filled',
                ]
            ],
            'established_date' => [
                'rules' => 'required|valid_date[dd/mm/yyyy]',
                'errors' => [
                    'required' => 'Established Date must be filled',
                    'valid_date' => 'field does not contain a valid date',
                ]
            ],
            'company_website' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Company Website must be filled',
                ]
            ],
            'supplier_category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Supplier Category must be filled',
                ]
            ],
            'vendor_code' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vendor Code must be filled',
                ]
            ],
            'join_date_maj' => [
                'rules' => 'required|valid_date[dd/mm/yyyy]',
                'errors' => [
                    'required' => 'Join Date must be filled',
                    'valid_date' => 'field does not contain a valid date',
                ]
            ],
            'supplier_group' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Supplier Group must be filled',
                ]
            ],
            'official_letter_attachment' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Official Letter must be filled',
                ]
            ],
            'country' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Country must be filled',
                ]
            ],
            'province' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Provience must be filled',
                ]
            ],
            'city' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'City must be filled',
                ]
            ],
            'zip_code' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'City must be filled',
                ]
            ],
            'supplier_affiliation' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Supplier Affilation must be filled',
                ]
            ],
            'company_phone_number' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Company Phone Number must be filled',
                    'numeric' => 'field contains anything other than numeric characters',
                    'min_length' => 'field is shorter than the parameter value', 
                    'max_length' => 'field is longer than the parameter value',
                ]
            ],
            'company_fax_number' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Company Fax Number must be filled',
                    'numeric' => 'field contains anything other than numeric characters',
                    'min_length' => 'field is shorter than the parameter value', 
                    'max_length' => 'field is longer than the parameter value',
                ]
            ],
            'logo_attachment' => [
                'rules' => 'uploaded[logo_attachment]',
                'errors' => [
                    'uploaded' => 'Image must be filled',
                ]
            ],
            'capital' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Capital must be filled',
                    'numeric' => 'field contains anything other than numeric characters',
                    'greater_than_equal_to' => 'field is less than the parameter value',
                ]
            ],
            'asset_value' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Assets Value must be filled',
                    'numeric' => 'field contains anything other than numeric characters',
                    'greater_than_equal_to' => 'field is less than the parameter value',
                ]
            ],
            'company_clasification' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Company Clasification must be filled',
                ]
            ],
            'technical_assistant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'technical Assistant must be filled',
                ]
            ],
            'start_operation_date' => [
                'rules' => 'required|valid_date[dd/mm/yyyy]',
                'errors' => [
                    'required' => 'Start Operation Date must be filled',
                    'valid_date' => 'field does not contain a valid date',
                ]
            ],
            'currency' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Currency must be filled',
                ]
            ],
            'cp_username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Contact Person Username must be filled',
                ]
            ],
            'contact_person_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Contact Person Name must be filled',
                ]
            ],
            'contact_number|numeric|min_length[10]|max_length[15]' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Contact Person Number must be filled',
                    'numeric' => 'field contains anything other than numeric characters',
                    'min_length' => 'field is shorter than the parameter value', 
                    'max_length' => 'field is longer than the parameter value',
                ]
            ],
            'contact_person_title' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Contact Person Title must be filled',
                ]
            ],
            'contact_person_email_1' => [
                'rules' => 'required|valid_email|valid_emails',
                'errors' => [
                    'required' => 'Contact Person Email 1 must be filled',
                    'valid_email' => 'field does not contain a valid email address',
                    'valid_emails' => 'value provided in a comma separated list is not a valid email'
                ]
            ],
            'contact_person_email_2' => [
                'rules' => 'required|valid_email|valid_emails',
                'errors' => [
                    'required' => 'Contact Person Email 2 must be filled',
                    'valid_email' => 'field does not contain a valid email address',
                    'valid_emails' => 'value provided in a comma separated list is not a valid email'
                ]
            ],
            'address' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Address must be filled',
                ]
            ],
        ];

        // $path = $this->request->getFile('logo_attachment')->store('img/customer', $user['username'] . '.' . 'png');

        if($this->validate($rules)){
            $logo = $this->request->getFile('logo_attachment');
            $logo->move(FCPATH . 'img/customer', $user['username'] . date('YmdHis') . '.png');

            if($this->request->getMethod() == 'post'){
                $data = [
                    'company_title' => $this->request->getPost('company_title'),
                    'company_name' => $this->request->getPost('company_name'),
                    'npwp_number' => $this->request->getPost('npwp_number'),
                    'abbreviated_name' => $this->request->getPost('abbreviated_name'),
                    'abbreviated_supplier' => $this->request->getPost('supplier_abbreviated'),
                    'estabilished_date' => $this->request->getPost('established_date'),
                    'company_website' => $this->request->getPost('company_website'),
                    'supplier_category' => $this->request->getPost('supplier_category'),
                    'vendor_code' => $this->request->getPost('vendor_code'),
                    'join_date' => $this->request->getPost('join_date_maj'),
                    'supplier_group' => $this->request->getPost('supplier_group'),
                    'official_letter_attachment' => $this->request->getPost('official_letter_attachment'),
                    'country' => $this->request->getPost('country'),
                    'provience' => $this->request->getPost('province'),
                    'city' => $this->request->getPost('city'),
                    'zip_code' => $this->request->getPost('zip_code'),
                    'supplier_affiliation' => $this->request->getPost('supplier_affiliation'),
                    'company_phone_number' => $this->request->getPost('company_phone_number'),
                    'company_fax_number' => $this->request->getPost('company_fax_number'),
                    'logo_attachment' => $user['username'] . date('YmdHis') . '.png',
                    'capital' => $this->request->getPost('capital'),
                    'asset_value' => $this->request->getPost('asset_value'),
                    'company_clasification' => $this->request->getPost('company_clasification'),
                    'technical_assistant' => $this->request->getPost('technical_assistant'),
                    'start_operation_date' => $this->request->getPost('start_operation_date'),
                    'currency' => $this->request->getPost('currency'),
                    'cp_username' => $this->request->getPost('cp_username'),
                    'cp_name' => $this->request->getPost('contact_person_name'),
                    'cp_number' => $this->request->getPost('contact_number'),
                    'cp_title' => $this->request->getPost('contact_person_title'),
                    'cp_email1' => $this->request->getPost('contact_person_email_1'),
                    'cp_email2' => $this->request->getPost('contact_person_email_2'),
                    'address' => $this->request->getPost('address'),
                ];
                
                if($M_auth->update($id, $data)){
                    session()->setFlashdata('success', 'Data berhasil disimpan');
                    return redirect()->to('users');
                }
            }
        } else {
            $data['validation'] = $this->validator;
            $this->ionAuth = new IonAuth();
            $M_auth = new M_auth();
            $data['title'] = 'Edit';
            $data['current_user'] = $this->ionAuth->user()->row();
            return $this->_render_page('user/profile/profile_edit', $data);
        }
        
    }


    public function upload_user()
    {
        helper('form', 'url', 'upload');
        $data['ionAuth'] = $this->ionAuth;
        $data['current_user'] = $this->ionAuth->user()->row();
        $data['title'] = 'Upload Profile';
        return $this->_render_page('user/upload-user/upload_user', $data);
    }


    public function stored_user()
    {
        helper('form', 'url', 'upload');
        $data['ionAuth'] = $this->ionAuth;
        $M_auth = new M_auth();
        $file_mimes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/xls', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if ($this->request->getFile('fileUpload')->isValid() && in_array($this->request->getFile('fileUpload')->getMimeType(), $file_mimes)) {
            $notif['notif_status']  = false;

            $file = $this->request->getFile('fileUpload');
            $spreadsheet    = IOFactory::load($file->getPathname());
            $sheetData      = $spreadsheet->getActiveSheet()->toArray();
            $line           = 1;

            $excel_header = array_flip(array(
                'username'		            => "Username",
                'temp'                      => "Password",
                'email'       	            => "Email",
                'password'                  => "Hash Code",
            ));

            foreach (array_slice($sheetData, 0, 1) as $key => $value) {
				foreach ($value as $k => $v) {
					if ($value != "") {
						$array_key[$k] = $excel_header[$v];
					}
				}
			}
			foreach (array_slice($sheetData, 1) as $key => $value) {
				if ($value[0] != "") {
					foreach ($value as $k => $v) {
						$new_value[$array_key[$k]] = $v;
                        var_dump($new_value[$array_key[$k]]);
					}
                    $data = array(
                        'id'        => $M_auth->generateId(),
                        'username'  => $new_value['username'],
                        'password'  => $new_value['password'],
                        'email'     => $new_value['email'],
                        'active'    => '1',
                    );
                    $M_auth = new M_auth();
                    $insert = $M_auth->datainsert($data);
                    $insert = true;
                }
                $line++;
            }
            return redirect()->to('dashboard');
        } else {
            return redirect()->to('dashboard');
        }
    }

    public function temp()
    {
        $this->ionAuth    = new \IonAuth\Libraries\IonAuth();
        $user = $this->ionAuth->user()->row();
        echo $user->email;
    }

    public function getpart()
    {
        $output['data'] = [];
        $M_curl = new M_curl();
        
        $this->SAP_PARAMS['function'] = 'Z_QC';
        // Request pertama
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_MATNR_FERT',
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);

        if ($sap['success']) {
            $output['data'] = (isset($sap['data']['ZTBL_SELECTION'])) ? $sap['data']['ZTBL_SELECTION'] : [];
        }

        // Request kedua
        $this->SAP_PARAMS['params'] = [
            'RPT' => 'P_MATNR_HALB',
        ];

        $sap = $M_curl->execute("POST", $this->SAP_PARAMS);

        if ($sap['success']) {
            if (isset($sap['data']['ZTBL_SELECTION'])) {
                $output['data'] = array_merge($output['data'], $sap['data']['ZTBL_SELECTION']);
            }
        }

        print_r($output);
    }
    
}
