<?php

namespace App\Models;

use CodeIgniter\Model;

class CostumersModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'costumers';
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

    public function getAllCostumer()
    {
        $builder = $this->db->table('costumers');

        $builder->orderBy('name', 'ASC');
        $query = $builder->get()->getResultArray();

        return $query;
    }

    public function addCostumer($data)
    {
        $builder = $this->db->table('costumers');

        $builder->insert($data);

        return $this->db->affectedRows();
    }

    public function deleteCostumer($id)
    {
        $builder = $this->db->table('costumers');

        $builder->delete(['id' => $id]);

        return $this->db->affectedRows();
    }
}
