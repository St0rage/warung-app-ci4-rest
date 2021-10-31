<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoriesModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'product_categories';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'product_id',
        'category_id'
    ];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    public function addProductCategories($data, $prodId)
    {
        $builder = $this->db->table('product_categories');

        $newData = [];
        foreach ($data as $value) {
            $newData[] = ['product_id' => $prodId, 'category_id' => $value];
        }

        $builder->insertBatch($newData);
    }

    public function updateProductCategories($data, $prodId)
    {
        $builder = $this->db->table('product_categories');
        $builder->delete(['product_id' => $prodId]);

        $this->addProductCategories($data, $prodId);
    }
}
