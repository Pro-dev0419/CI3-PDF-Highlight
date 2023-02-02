<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PDF2Controller extends CI_Controller { 
    public function abcd() { 
        echo 123; die;
        $this->load->view('home-view');
    }
}