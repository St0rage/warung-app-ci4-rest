<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProductCategoriesModel;

class ProductsModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'products';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'product_name',
        'price',
        'image',
        'created_at'
    ];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
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

    public function getAll($limit)
    {
        $builder = $this->db->table('products');

        $builder->limit($limit['limit'], empty($limit['page']) ? 0 : $limit['page']);
        $builder->orderBy('updated_at', 'DESC');
        $query = $builder->get()->getResultArray();

        return $query;
    }

    public function getSingleProduct($id)
    {
        return $this->find($id);
    }

    public function getProductByCategory($id, $limit)
    {
        $builder = $this->db->table('products');

        $builder->select('products.*');
        $builder->join('product_categories', 'products.id = product_categories.product_id');
        $builder->where('product_categories.category_id', $id);
        $builder->limit($limit['limit'], empty($limit['page']) ? 0 : $limit['page']);
        $builder->orderBy('products.created_at');

        return $builder->get()->getResultArray();
    }

    public function countAllProducts()
    {
        return $this->db->table('products')->countAll();
    }

    public function countProductsByCategory($id)
    {
        $builder = $this->db->table('products');

        $builder->select('products.*');
        $builder->join('product_categories', 'products.id = product_categories.product_id');
        $builder->where('product_categories.category_id', $id);

        return $builder->get()->getNumRows();
    }

    public function addProduct($data)
    {
        $builder = $this->db->table('products');
        $productCategoriesModel = new ProductCategoriesModel();
        $categories = $data['category_id'];
        // array_pop($data);
        unset($data['category_id']);


        $builder->insert($data);
        $row = $this->db->affectedRows();
        $prodId = $this->db->insertID();
        if ($row > 0) {
            $productCategoriesModel->addProductCategories($categories, $prodId);
        }

        return $row;
    }

    public function updateProduct($data)
    {
        $builder = $this->db->table('products');
        $productCategoriesModel = new ProductCategoriesModel();
        $categories = $data['category_id'];
        // array_pop($data);
        unset($data['category_id']);

        $builder->replace($data);
        $row = $this->db->affectedRows();
        $prodId = $data['id'];
        if ($row > 0) {
            $productCategoriesModel->updateProductCategories($categories, $prodId);
        }

        return $row;
    }

    public function deleteProduct($id)
    {
        $builder = $this->db->table('products');

        $builder->delete(['id' => $id]);

        return $this->db->affectedRows();
    }

    // SEARCH
    public function search($data)
    {
        $builder = $this->db->table('products');

        if ($data == '') {
            $builder->limit(5, 0);
        } else {
            $builder->like('product_name', $data);
        }

        // if ($data != '') {
        //     $builder->like('product_name', $data);
        // }
        
        $builder->orderBy('updated_at', 'DESC');
        return $builder->get();
    }

    public function searchByCategory($data, $id)
    {
        $builder = $this->db->table('products');

        $builder->select('products.*');
        $builder->join('product_categories', 'products.id = product_categories.product_id');
        $builder->where('product_categories.category_id', $id);
        if ($data != '') {
            $builder->like('products.product_name', $data);
        } else {
            $builder->limit(5, 0);
        }
        $builder->orderBy('created_at', 'DESC');
        return $builder->get();
    }
}
