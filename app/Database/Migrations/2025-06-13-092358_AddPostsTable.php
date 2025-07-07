<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class AddPostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150,  // Reduced

            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'unique' => true,
            ],
            'meta_description' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'thumbnail_path' => [
                'type' => 'VARCHAR',
                'constraint' => 200,  // Reduced
                'null' => false
            ],
            'thumbnail_caption' => [
                'type' => 'VARCHAR',
                'constraint' => 200,  // Reduced
                'null' => false
            ],
            'content' => [
                'type' => 'LONGTEXT'
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('username', 'users', 'username');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE');
        $this->forge->createTable('posts', true);
    }

    public function down()
    {
        //
        $this->forge->dropTable('posts');
    }
}
