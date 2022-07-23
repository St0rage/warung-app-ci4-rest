<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');

use CodeIgniter\RESTful\ResourceController;

class Categories extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\CategoriesModel';

    public function index()
    {

        $get = $this->model->getAll();

        if ($get) {
            $response = [
                'status' => 200,
                'error' => false,
                'data' => $get
            ];

            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => 200,
                'error' => false,
                'data' => []
            ];

            return $this->respond($response, 200);
        }
    }

    public function create()
    {

        $validation = \Config\Services::validation();

        $category_name = $this->request->getPost('category_name');

        $data = [
            'category_name' => $category_name
        ];

        if (!$validation->run($data, 'categories')) {
            $response = [
                'status' => 400,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, 400);
        } else {
            $post = $this->model->addCategories($data);

            if ($post > 0) {
                $code = 201;
                $message = "Kategori $category_name berhasil ditambahkan";
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => [
                        'message' => $message
                    ]
                ];
            } else {
                $code = 400;
                $response = [
                    'status' => $code,
                    'error' => true,
                    'data' => [
                        'message' => 'Kategori gagal ditambahkan'
                    ]
                ];
            }

            return $this->respond($response, $code);
        }
    }

    public function remove($id = null)
    {

        $delete = $this->model->deleteCategory($id);
        if ($delete > 0) {
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => 'Kategori Berhasil dihapus'
                ]
            ];
        } else {
            $code = 404;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => [
                    'message' => 'Gagal, Masih Terdapat Produk Dengan Kategori Tersebut'
                ]
            ];
        }

        return $this->respond($response, $code);
    }
}
