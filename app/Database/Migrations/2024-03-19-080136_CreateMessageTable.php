<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessageTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT ',
                'auto_increment' => true,
            ],
            'content' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'send_user_id' => [
                'type' => 'INT',
            ],
            'receive_user_id' => [
                'type' => 'INT',
            ],
            'id_image' => [
                'type' => 'INT',
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addForeignKey('id', 'users', 'send_user_id');
        $this->forge->addForeignKey('id', 'users', 'Receive_user_id');
        $this->forge->addForeignKey('id', 'file', 'id_image');
        $this->forge->addKey('id', true);
        $this->forge->createTable('message');
    }

    public function down()
    {
        $this->forge->dropForeignKey('file', 'id_image');
        $this->forge->dropForeignKey('users', 'send_user_id');
        $this->forge->dropForeignKey('users', 'Receive_user_id');
        $this->forge->dropTable('message');
    }
}
