<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pdf extends CI_Model
{
        public function __construct(){
                parent::__construct();
                $this->load->database();
        }
        public $table = "pdfs";
        public function insertPdf($data)
        {
                return $this->db->insert($this->table, $data);
        }
        public function getData()
        {
                $query = $this->db->get($this->table);
                return $query->result_array();
        }
        public function find($id)
        {
                $query = $this->db->where('id', $id)->get($this->table);
                return $query->row_array();
        }
}
