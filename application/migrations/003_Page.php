<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Page extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'start_offset' => [
                'type' => 'INT',
            ],
            'end_offset' => [
                'type' => 'INT',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('highlights');
    }

    public function down()
    {
        $this->dbforge->drop_table('highlights');
    }
}