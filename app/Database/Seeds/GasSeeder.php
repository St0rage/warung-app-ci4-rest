<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use phpDocumentor\Reflection\Types\This;

class GasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Gas Elpiji 3Kg',
                'price' => 21000
            ],
            [
                'name' => 'Gas Elpiji 12Kg',
                'price' => 150000
            ],
            [
                'name' => 'Gas Elpiji 5Kg',
                'price' => 100000
            ]
        ];

        $this->db->table('gas')->insertBatch($data);
    }
}
