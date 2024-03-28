<?php

namespace App\Models;

use CodeIgniter\Model;

class M_invoicing extends Model
{
    protected $table = 'gr'; 
    protected $primaryKey = 'id'; 
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['gr_number', 'delivery_number', 'currency', 'po_number', 'is_verified', 'date'];

    public function getData($startDate = null, $endDate = null)
    {
        $startDate = ($startDate === '') ? null : $startDate;
        $endDate = ($endDate === '') ? null : $endDate;
        
        if ($startDate !== null && $endDate !== null) {
            return $this->where('del_note_date >=', $startDate)
                        ->where('del_note_date <=', $endDate)
                        ->findAll();
        } else {
            return $this->findAll();
        }
    }

    public function getGRbyID($id)
    {
        return $this->find($id);
    }

    public function updateGRData($id, $data)
    {
        return $this->update($id, $data);
    }
}