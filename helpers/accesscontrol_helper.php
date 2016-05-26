<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function is_logged_in() {
    
    $is_logged_in = $this->session->userdata('user_id');
    if (!isset($is_logged_in) || $is_logged_in != true) {
        echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';
        die();
        //$this->load->view('login_form');
    }
}
