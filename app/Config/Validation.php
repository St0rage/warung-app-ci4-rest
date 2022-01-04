<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;
use App\Validation\WrgRules;

class Validation
{
    //--------------------------------------------------------------------
    // Setup
    //--------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        // Costum Validation
        WrgRules::class
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    //--------------------------------------------------------------------
    // Rules
    //--------------------------------------------------------------------

    public $categories = [
        'category_name' => 'required|min_length[3]|is_unique[categories.category_name]'
    ];

    public $categories_errors = [
        'category_name' => [
            'required' => 'Nama Kategori Wajib diisi',
            'min_length' => 'Minimal Panjang Kategori 3 Huruf',
            'is_unique' => 'Nama Kategori yang Dimasukan Sudah Ada'
        ]
    ];

    public $products = [
        'product_name' => 'required|min_length[4]|is_unique[products.product_name]',
        'price' => 'required|numeric|price_check',
        'category_id.*' => 'required',
        'image' => 'img_check'
    ];

    public $products_errors = [
        'product_name' => [
            'required' => 'Nama Produk Wajib diisi',
            'min_length' => 'Minimal Panjang Produk 4 Huruf',
            'is_unique' => 'Nama Produk yang Dimasukan Sudah Ada'
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
            'img_check' => 'Yang Pilih Bukan Gambar'
        ]
    ];
}
