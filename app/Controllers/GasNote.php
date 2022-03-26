<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');

use CodeIgniter\RESTful\ResourceController;
use App\Models\GasModel;

class GasNote extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\GasNoteModel';

    public function __construct()
    {
        helper(['auth_helper']);
    }

    public function getAllNote($status = 0)
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $get = $this->model->getAllNote($status);

        $code = 200;
        $response = [
            'status' => $code,
            'error' => false,
            'data' => $get
        ];

        return $this->respond($response, $code);
    }

    public function getDetail($id = null)
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $get = $this->model->getDetail($id);

        if (!$get) {
            $code = 404;
            $message = 'Catatan tidak ditemukan';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get
            ];
        }

        return $this->respond($response, $code);
    }

    public function create()
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $validation = \Config\Services::validation();

        $name = $this->request->getPost('name');
        $qty = $this->request->getPost('quantity');
        $gas_id = $this->request->getPost('gas_id');

        $data = [
            'name' => $name,
            'quantity' => $qty,
            'gas_id' => $gas_id,
        ];

        if (!$validation->run($data, 'gasCreate')) {
            $code = 400;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, $code);
        }

        $gasModel = new GasModel();
        $getGasPrice = $gasModel->getPrice($data['gas_id']);

        $total = $getGasPrice[0]['price'] * $data['quantity'];

        $data['total'] = $total;
        $data['status'] = 0;
        $data['created_at'] = time();


        $post = $this->model->createGas($data);

        if ($post == 1) {
            $code = 201;
            $message = 'Penitipan baru berhasil dibuat';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Penitipan gagal ditambahkan';
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

    public function update($id = null)
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $validation = \Config\Services::validation();

        $data = $this->request->getRawInput();

        $validation->setRules(
            [
                'quantity' => 'required|numeric|greater_than[0]|qty_check['. $id .']'
            ],
            [
                'quantity' => [
                    'required' => 'Kuantitas Wajib diisi',
                    'numeric' => 'Kuantitas Wajib berupa Angka',
                    'greater_than' => 'Kuantitas harus Minimal 1',
                    'qty_check' => 'Kuantitas tidak boleh sama dari sebelumnya'
                ],
            ]
        );

        if (!$validation->run($data)) {
            $code = 400;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, $code);
        }

        $gasModel = new GasModel();

        $getGasPrice = $gasModel->getPrice($data['gas_id']);

        $price = $getGasPrice[0]['price'] * $data['quantity'];

        $newData = [
            'quantity' => $data['quantity'],
            'total' => $price,
            'updated_at' => time(),
        ];

        $put = $this->model->updateNote($newData, $id);

        if ($put > 0) {
            $code = 201;
            $message = 'Catatan berhasil diubah';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Catatan gagal diubah';
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

    public function statusUpdate($id = null)
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $put = $this->model->updateNoteStatus($id);

        if ($put > 0) {
            $code = 201;
            $message = 'Status catatan berhasil dirubah';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Status catatan gagal dirubah';
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

    public function delete($id = null)
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $delete = $this->model->deleteNote($id);
        
        if ($delete > 0) {
            $code = 200;
            $message = 'Catatan berhasil dihapus';
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => $message
                ]
            ];
        } else {
            $code = 400;
            $message = 'Catatan gagal dihapus';
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

    public function searchNotes()
    {
        // TOKEN VERIFY
        $auth = verifyToken($this->request);

        if ($auth[0] == false) {
            return $this->respond($auth[1], 401);
        }
        // END TOKEN VERIFY

        $keyword = '';
        $status = $this->request->getPost('status');

        if ($this->request->getPost('keyword')) {
            $keyword = $this->request->getPost('keyword');
        } else {
            $keyword = '';
        }

        $search = $this->model->searchNotes($keyword, $status);

        if ($search->getNumRows() > 0) {
            $search = $search->getResultArray();

            $response = [
                'status' => 200,
                'error' => false,
                'data' => $search
            ];
        } else {
            $response = [
                'status' => 200,
                'error' => false,
                'data' => []
            ];
        }

        return $this->respond($response, 200);

    }
}
