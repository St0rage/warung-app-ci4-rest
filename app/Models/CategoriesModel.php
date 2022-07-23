<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'categories';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'category_name'
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

    public function getAll()
    {
        $builder = $this->db->table('categories');

        $builder->orderBy('category_name', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getProductCategory($id)
    {
        $builder = $this->db->table('categories');

        $builder->select('categories.*');
        $builder->join('product_categories', 'categories.id = product_categories.category_id');
        $builder->where('product_categories.product_id', $id);

        return $builder->get()->getResultArray();
    }

    public function addCategories($data)
    {
        $builder = $this->db->table('categories');

        $builder->insert($data);
        return $this->db->affectedRows();
    }

    public function deleteCategory($id)
    {
        $builder = $this->db->table('categories');

        $productCategoriesModel = new ProductCategoriesModel();
        $checkProduct = $productCategoriesModel->checkProduct($id);

        if ($checkProduct > 0) {
            return 0;
        } else {
            $builder->delete(['id' => $id]);

            return $this->db->affectedRows();
        }
    }
}
