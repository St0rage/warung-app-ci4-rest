<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Costumers extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\CostumersModel';

    public function getAllCostumer()
    {
        $get = $this->model->getAllCostumer();

        $response = [
            'status' => 200,
            'error' => false,
            'data' => $get
        ];

        return $this->respond($response, 200);
        
    }

    public function addCostumer()
    {
        $validation = \Config\Services::validation();

        $data = [
            'name' => $this->request->getPost('name')
        ];

        $validation->setRules(
            [
                'name' => 'required|min_length[4]'
            ],
            [
                'name' => [
                    'required' => 'Nama Tidak Boleh Kosong',
                    'min_length' => 'Minimal Panjang Nama 4 Huruf'
                ]
            ]
        );

        if (!$validation->run($data)) {
            $response = [
                'status' => 400,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, 400);
        }

        $post = $this->model->addCostumer($data);

        if ($post > 0) {
            $code = 201;
            $message = 'Pelanggan ' .  $this->request->getPost('name')  . ' Berhasil ditambahkan';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Pelanggan Baru Gagal ditambahkan';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        }

        return $this->respond($response, $code);
    }

    public function delete($id = null)
    {
        $delete = $this->model->deleteCostumer($id);

        if ($delete > 0) {
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => 'Pelanggan Berhasil dihapus'
                ]
            ];
        } else {
            $code = 400;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => [
                    'message' => 'Pelanggan tersebut gagal dihapus / masih mempunyai catatan'
                ]
            ];
        }
        return $this->respond($response, $code);
    }
}
