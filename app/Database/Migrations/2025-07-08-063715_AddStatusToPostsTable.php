<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToPostsTable extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['published', 'draft'],
                'null' => false,
            ],
        ];

        $this->forge->addColumn('posts', $fields);
    }

    public function down()
    {
        //
        $this->forge->dropColumn('posts', 'status');
    }
}
