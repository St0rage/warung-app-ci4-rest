<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');


use CodeIgniter\RESTful\ResourceController;

class Gas extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\GasModel';

    public function __construct()
    {
        helper(['gas_helper', 'auth_helper']);
    }

    public function index()
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

        $get = $this->model->getAll();

        if ($get) {
            
            $code = 200;

            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get
            ];

        }   

        return $this->respond($response, $code);
    }

    public function getSingleGas($id)
    {   
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

        $get = $this->model->getSingleGas($id);

        if ($get) {
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get
            ];
        }

        return $this->respond($response, $code);
    }

    public function updateGasPrice($id) 
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respondCreated($auth[1]);
        }
        // END TOKEN VERIFY

        $validation = \Config\Services::validation();

        $price = $this->request->getRawInput('price');
        
        $validation->setRules(
            [
                'price' => 'required|numeric|price_check'
            ],
            [
                'price' => [
                    'required' => 'Harga Wajib diisi',
                    'numeric' => 'Harga tidak sesuai',
                    'price_check' => 'Minimal Harga Rp500 ke atas'
                ]
            ]
        );

        if (!$validation->run($price)) {
            $code = 400; 
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, $code);
        }

        $put = $this->model->updateGasPrice($price, $id);

        if ($put > 0) {
            updateTotal($id);
            $code = 201;
            $message = 'Harga berhasil diubah';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Harga gagal diubah';
            $response = [
                'status' => $code,
                'error' => true,
                'data' => [
                    'message' => $message
                ]
            ];
        }

        return $this->respond($response, $code);

    }
}
