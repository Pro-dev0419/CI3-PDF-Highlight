<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Model
{
        public $table = "tests";
        public function __construct(){
                parent::__construct();
                $this->load->database();
        }
        public function findAll($id)
        {
                $query = $this->db->where('pdf_id', $id)->get($this->table);
                return $query->result_array();
        }
        
        public function deleteItem($id)
        {
                return $this->db->delete($this->table, array('pdf_id' => $id));
        }
        public function insertData($data)
        {
                return $this->db->insert($this->table, $data);
        }
}
