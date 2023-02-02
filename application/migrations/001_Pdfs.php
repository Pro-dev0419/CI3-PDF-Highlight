<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Pdfs extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(
            [

                'id' => [
                    'type' => 'INT',
                    'constraint' => 5,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ],
                'url' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ],
            ]
        );
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('pdfs');
    }

    public function down()
    {
        $this->dbforge->drop_table('pdfs');
    }
}
