<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function index() {
        exit('No direct script access allowed');
       
    }

    public function login() {
        $this->load->helper('url');
        if(!$this->session->userdata('authorized')) {
            $this->load->view('login_admin');

        } else {
            redirect('http://www.openyearround.co.kr:2019/apply/viewer');
        }
    }

    public function logout() {
        $this->load->helper('url');
        $this->session->sess_destroy();
        redirect('http://www.openyearround.co.kr:2019/apply/admin/login');
    }
}
?>