<?php

namespace App\Models;

use CodeIgniter\Model;

class GasNoteModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'gasnotes';
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

    public function getAllNote($status)
    {
        $builder = $this->db->table('gas_note');

        $builder->select('gas_note.id, gas_note.name, gas_note.quantity, gas_note.status, gas_note.created_at, gas_note.updated_at, gas_note.taken_at, gas.name as gas_name');
        $builder->join('gas', 'gas_note.gas_id = gas.id');
        $builder->where('gas_note.status', $status);
        if ($status == 0) {
            $builder->orderBy('(FROM_UNIXTIME(created_at))', 'DESC');
        } else {
            $builder->orderBy('(FROM_UNIXTIME(taken_at))', 'DESC');
        }

        return $builder->get()->getResultArray();
    }

    public function getDetail($id)
    {
        $builder = $this->db->table('gas_note');

        $builder->select('gas_note.*, gas.name as gas_name, gas.price');
        $builder->join('gas', 'gas_note.gas_id = gas.id');
        $builder->where('gas_note.id', $id);

        return $builder->get()->getResultArray();
    }

    public function createGas($data)
    {
        $builder = $this->db->table('gas_note');

        $builder->insert($data);
        $row = $this->db->affectedRows();

        return $row;
    }

    public function updateNote($data, $id)
    {
        $builder = $this->db->table('gas_note');

        $builder->where('id', $id);
        $builder->update($data);
        $row = $this->db->affectedRows();

        return $row;
    }

    public function updateNoteStatus($id)
    {
        $builder = $this->db->table('gas_note');

        $builder->set('status', 1);
        $builder->set('taken_at', time());
        $builder->where('id', $id);
        $builder->update();
        $row = $this->db->affectedRows();

        return $row;
    }

    public function deleteNote($id)
    {
        $builder = $this->db->table('gas_note');

        $builder->delete(['id' => $id]);
        
        return $this->db->affectedRows();
    }

    public function searchNotes($keyword, $status)
    {
        $builder = $this->db->table('gas_note');

        $builder->select('gas_note.id, gas_note.name, gas_note.quantity, gas_note.status, gas_note.created_at, gas_note.updated_at, gas_note.taken_at, gas.name as gas_name');
        $builder->join('gas', 'gas_note.gas_id = gas.id');
        $builder->where('gas_note.status', $status);
        if ($keyword != '') {
            $builder->like('gas_note.name', $keyword);
        }

        if ($status == 0) {
            $builder->orderBy('(FROM_UNIXTIME(created_at))', 'DESC');
        } else {
            $builder->orderBy('(FROM_UNIXTIME(taken_at))', 'DESC');
        }

        return $builder->get();
    }
}
