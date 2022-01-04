<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $dummy = ['Makanan', 'Minuman', 'Rokok', 'Obat-Obatan', 'Rokok', 'Biskuit'];

        for ($i = 0; $i < count($dummy); $i++) {
            $data = [
                'category_name' => $dummy[$i]
            ];

            // Using Query Builder
            $this->db->table('categories')->insert($data);
        }
    }
}
