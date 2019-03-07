<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewer extends CI_Controller {
    public function index() {
        $this->load->helper('url');
        if(!$this->session->userdata('authorized')) {
            redirect('http://www.openyearround.co.kr:2019/apply/admin/login');
        }

        $this->load->view('viewer_application');
    }
}
?>