<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Categories extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\CategoriesModel';

    public function __construct()
    {
        helper('auth_helper');
    }

    public function index()
    {
        // TOKEN VERIFY
        // if (!$this->request->hasHeader("Authorization")) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak'
        //     ];

        //     return $this->respondCreated($response);
        // } else {
        //     $token = $this->request->header("Authorization");
        // }

        // $verify = verifyToken($token->getValue());

        // if ($verify === false) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak Token Tidak Valid'
        //     ];

        //     return $this->respondCreated($response);
        // }
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

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
                'status' => 404,
                'error' => true,
                'data' => []
            ];

            return $this->respond($response, 404);
        }
    }

    public function create()
    {
        // TOKEN VERIFY
        // if (!$this->request->hasHeader("Authorization")) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak'
        //     ];

        //     return $this->respondCreated($response);
        // } else {
        //     $token = $this->request->header("Authorization");
        // }

        // $verify = verifyToken($token->getValue());

        // if ($verify === false) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak Token Tidak Valid'
        //     ];

        //     return $this->respondCreated($response);
        // }
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

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
        // TOKEN VERIFY
        // if (!$this->request->hasHeader("Authorization")) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak'
        //     ];

        //     return $this->respondCreated($response);
        // } else {
        //     $token = $this->request->header("Authorization");
        // }

        // $verify = verifyToken($token->getValue());

        // if ($verify === false) {
        //     $response = [
        //         'status' => 401,
        //         'error' => true,
        //         'message' => 'Akses Ditolak Token Tidak Valid'
        //     ];

        //     return $this->respondCreated($response);
        // }
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

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
                    'message' => 'Kategori tidak ditemukan'
                ]
            ];
        }

        return $this->respond($response, $code);
    }
}
