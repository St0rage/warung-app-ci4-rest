<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GasNote extends Migration
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
                'constraint' => 100,
                'null' => false
            ],
            'gas_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'unsigned' => true
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 100,
                'null' => false
            ],
            'total' => [
                'type' => 'INT',
                'constraint' => 100,
                'null' => false
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'updated_at' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'taken_at' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('gas_id', 'gas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('gas_note');
    }

    public function down()
    {
        //
    }
}
