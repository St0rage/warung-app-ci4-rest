<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoriesModel;
use DateTime;
use DateTimeZone;

class Products extends ResourceController
{
    protected $format = 'json';
    protected $modelName = 'App\Models\ProductsModel';

    public function __construct()
    {
        helper('image_helper');
    }

    public function index()
    {

        $products = [
            'limit' => $this->request->getVar('limit'),
            'start' => $this->request->getVar('start')
        ];

        $get = $this->model->getAll($products);

        if ($get) {

            $categoriesModel = new CategoriesModel();

            for ($i = 0; $i < count($get); $i++) {
                $imgTmp = $get[$i]['image'];
                // $get[$i]['image'] = base_url('img/' . $imgTmp);
                $get[$i]['image'] = generateImgUrl($imgTmp);


                $category = $categoriesModel->getProductCategory($get[$i]['id']);
                $get[$i]['category'] = $category;
            }

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
                'data' => $get
            ];

            return $this->respond($response, 200);
        }
    }

    public function getSingleProduct($id)
    {

        $get = $this->model->getSingleProduct($id);

        if ($get) {

            $categoriesModel = new CategoriesModel();

            $imgTmp = $get['image'];
            // $get['image'] = base_url('img/' . $imgTmp);
            $get['image'] = generateImgUrl($imgTmp);
            $category = $categoriesModel->getProductCategory($get['id']);
            $get['category'] = $category;

            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get
            ];
        } else {
            $code = 404;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => [
                    'message' => 'Data Produk Tidak Ditemukan'
                ]
            ];
        }

        return $this->respond($response, $code);
    }

    public function getProductByCategory($id)
    {

        $get = $this->model->getProductByCategory($id);

        if ($get) {

            $categoriesModel = new CategoriesModel();

            for ($i = 0; $i < count($get); $i++) {
                $imgTmp = $get[$i]['image'];
                // $get[$i]['image'] = base_url('/img/' . $imgTmp);
                $get[$i]['image'] = generateImgUrl($imgTmp);
                

                $category = $categoriesModel->getProductCategory($get[$i]['id']);
                $get[$i]['category'] = $category;
            }

            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get
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

    public function countProduct()
    {

        $get = $this->model->countAllProducts();

        $response = [
            'status' => 200,
            'error' => false,
            'data' => $get
        ];

        return $this->respond($response, 200);
    }

    public function countProductByCategory($id)
    {

        $get = $this->model->countProductsByCategory($id);

        $response = [
            'status' => 200,
            'error' => false,
            'data' => $get
        ];

        return $this->respond($response, 200);
    }

    public function addProduct()
    {

        $validation = \Config\Services::validation();

        $product_name = $this->request->getPost('product_name');
        $price = $this->request->getPost('price');
        $image = $this->request->getPost('image');
        $created_at = date("Y-m-d");
        $category_id = $this->request->getPost('category_id');

        $data = [
            'product_name' => $product_name,
            'price' => $price,
            'image' => $image,
            'created_at' => $created_at,
            'category_id' => $category_id,
        ];

        if (!$validation->run($data, 'products')) {
            $response = [
                'status' => 400,
                'error' => true,
                'data' => $validation->getErrors()
            ];

            return $this->respond($response, 400);
        } else {
            if ($data['image'] !== null) {
                $data['image'] = imgDecoding($data['image']);
            } else {
                $data['image'] = 'default.png';
            }

            $post = $this->model->addProduct($data);

            if ($post > 0) {
                $code = 201;
                $message = "Produk $product_name berhasil ditambahkan";
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
                        'message' => 'Produk gagal ditambahkan'
                    ]
                ];
            }

            return $this->respond($response, $code);
        }
    }

    public function updateProduct($id)
    {

        $checkId = $this->model->getSingleProduct($id);

        if (!$checkId) {
            $response = [
                'status' => 404,
                'error' => true,
                'data' => [
                    'message' => 'Data Produk Tidak Ditemukan'
                ]
            ];

            return $this->respond($response, 404);
        }

        $validation = \Config\Services::validation();

        $data = $this->request->getRawInput();

        $validation->setRules(
            [
                'product_name' => 'required|min_length[4]|prod_check[' . $id . ']',
                'price' => 'required|numeric|price_check',
                'category_id.*' => 'required',
                'image' => 'img_check'
            ],
            [
                'product_name' => [
                    'required' => 'Nama Produk Wajib diisi',
                    'min_length' => 'Minimal Panjang Produk 4 Huruf',
                    'prod_check' => 'Nama Produk yang Dimasukan Sudah Ada'
                ],
                'price' => [
                    'required' => 'Harga Produk Wajib diisi',
                    'numeric' => 'Harga Produk tidak sesuai',
                    'price_check' => 'Minimal harga produk harus Rp500 ke atas'
                ],
                'category_id.*' => [
                    'required' => 'Pilih Minimal satu Kategori'
                ],
                'image' => [
                    'img_check' => 'Yang dipilih Bukan Gambar'
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
        } else {
            $data['created_at'] = $checkId['created_at'];
            $data['id'] = $id;

            $dateUpdate = new DateTime("now", new DateTimeZone("Asia/Jakarta"));

            $data['updated_at'] = $dateUpdate->format("Y-m-d H:i:s");
            
            if (!isset($data['old_image'])) {
                // jika gambar tidak diubah
                // $data['image'] adalah link gambar
                $image = explode('/', $data['image']);
                $data['image'] = $image[count($image) - 1];
            } else {
                // jika gambar diubah
                // $data['image'] adalah base64
                $oldImage = explode('/', $data['old_image']);
                unset($data['old_image']);
                $data['image'] = imgDecoding($data['image']);
                if ($oldImage[count($oldImage) - 1] !== 'default.png') {
                    unlink(FCPATH . 'img/' . $oldImage[count($oldImage) - 1]);
                }
            }


            $put = $this->model->updateProduct($data);

            if ($put > 0) {
                $code = 201;
                $message = "Produk " . $data['product_name'] . " berhasil diubah";
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
                        'message' => 'Produk gagal diubah'
                    ]
                ];
            }

            return $this->respond($response, $code);
        }
    }

    public function deleteProduct($id)
    {

        $checkId = $this->model->getSingleProduct($id);

        if (!$checkId) {
            $response = [
                'status' => 404,
                'error' => true,
                'data' => [
                    'message' => 'Data Produk Tidak Ditemukan'
                ]
            ];

            return $this->respond($response, 404);
        }

        $prodName = $checkId['product_name'];
        $delete = $this->model->deleteProduct($id);

        if ($delete > 0) {
            $imgName = $checkId['image'];
            if ($imgName !== 'default.png') {
                unlink(FCPATH . 'img/' . $imgName);
            }

            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => [
                    'message' => 'Produk ' . $prodName . ' Berhasil dihapus'
                ]
            ];
        } else {
            $code = 404;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => [
                    'message' => 'Produk tidak ditemukan'
                ]
            ];
        }

        return $this->respond($response, $code);
    }

    // SEARCHING
    public function searchProduct()
    {

        $categoriesModel = new CategoriesModel();

        $keyword = '';

        if ($this->request->getPost('keyword')) {
            $keyword = $this->request->getPost('keyword');
        } else {
            $keyword = '';
        }

        $search = $this->model->search($keyword);

        if ($search->getNumRows() > 0) {
            $search = $search->getResultArray();

            for ($i = 0; $i < count($search); $i++) {
                $imgTmp = $search[$i]['image'];
                // $search[$i]['image'] = base_url('img/' . $imgTmp);
                $search[$i]['image'] = generateImgUrl($imgTmp);


                $category = $categoriesModel->getProductCategory($search[$i]['id']);
                $search[$i]['category'] = $category;
            }

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

    public function searchProductByCategory()
    {

        $categoriesModel = new CategoriesModel();

        $keyword = '';
        $categoryId = $this->request->getPost('id-category');

        if ($this->request->getPost('keyword')) {
            $keyword = $this->request->getPost('keyword');
        }

        $search = $this->model->searchByCategory($keyword, $categoryId);

        if ($search->getNumRows() > 0) {
            $search = $search->getResultArray();

            for ($i = 0; $i < count($search); $i++) {
                $imgTmp = $search[$i]['image'];
                // $search[$i]['image'] = base_url('img/' . $imgTmp);
                $search[$i]['image'] = generateImgUrl($imgTmp);
                

                $category = $categoriesModel->getProductCategory($search[$i]['id']);
                $search[$i]['category'] = $category;
            }

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
