<?php

namespace App\Models;

use CodeIgniter\Model;

class M_auth extends Model
{
    protected $table = 'users'; // Gantilah 'nama_tabel' dengan nama tabel sebenarnya
    protected $primaryKey = 'id'; // Gantilah 'id' dengan primary key tabel Anda
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['username', 'password', 'company_title', 'company_name', 'npwp_number', 'abbreviated_name', 'abbreviated_supplier', 
    'estabilished_date', 'company_website', 'supplier_category', 'vendor_code', 'join_date', 'supplier_group', 'official_letter_attachment', 'country',
    'provience', 'city', 'zip_code', 'company_phone_number', 'company_fax_number', 'logo_attachment', 'capital', 'asset_value',
    'supplier_affiliation', 'company_clasification', 'technical_assistant', 'start_operation_date', 'currency', 'cp_username', 'cp_name',
    'cp_number', 'cp_title', 'cp_email1', 'cp_email2', 'address']; // Sesuaikan dengan kolom-kolom tabel Anda

    public function generateId()
    {
        // Mendapatkan ID terakhir dari tabel
        $lastId = $this->select('id')->orderBy('id', 'DESC')->first();

        // Jika tidak ada data, mulai dari ID 1
        if (!$lastId) {
            return 1;
        }

        // Menghasilkan ID baru dengan menambahkan 1 ke ID terakhir
        return $lastId['id'] + 1;
    }

    private function check_exist($data, $id = null)
    {
        $builder = $this->db->table($this->table);

        if ($id != null) {
            $builder->where($this->key . " != ", $id);
        }

        $builder->where('username', $data['username']);
        $this->exist = $builder->countAllResults();

        if ($this->exist) {
            return true;
        }

        return false;
    }

    public function datainsert($data)
    {
        if ($data === null) {
            return false;
        } else {
            if ($this->check_exist($data)) {
                return false;
            }

            $this->db->transStart();
            $this->db->table($this->table)->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return false;
            } else {
                $this->db->transCommit();
                return true;
            }
        }
    }

}