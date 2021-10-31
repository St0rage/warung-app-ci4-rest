<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false
            ],
            'product_id'       => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'unsigned'  => true
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'unsigned'  => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_categories');
    }

    public function down()
    {
        //
    }
}
