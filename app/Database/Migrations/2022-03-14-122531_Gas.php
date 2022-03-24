<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Gas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'price' => [
                'type' => 'INT',
                'constraint' => 100,
                'null' => false
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gas');
    }

    public function down()
    {
        //
    }
}
