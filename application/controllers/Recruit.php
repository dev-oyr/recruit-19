<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Recruit extends CI_Controller {
    public function index() {
        $this->load->view('recruit');
    }
}
?>