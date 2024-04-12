<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFileTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT ',
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'directory' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('file');
    }

    public function down()
    {
        $this->forge->dropTable('file');
    }
}
