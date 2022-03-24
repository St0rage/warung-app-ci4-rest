<?php

namespace App\Models;

use CodeIgniter\Model;

class GasModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'gas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAll() 
    {
        $builder = $this->db->table('gas');

        $query = $builder->get()->getResultArray();

        return $query;
    }

    public function getSingleGas($id)
    {
        return $this->find($id);
    }

    public function updateGasPrice($data, $id)
    {
        $builder = $this->db->table('gas');

        $builder->where('id', $id);
        $builder->update($data);
        $row = $this->db->affectedRows();

        return $row;
    }

    public function getPrice($gas_id)
    {
        $builder = $this->db->table('gas');

        $query = $builder->select('price');
        $query->where('id', $gas_id);
        
        return $query->get()->getResultArray();
    }
}

