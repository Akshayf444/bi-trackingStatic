<?php

class Admin_Controller extends CI_Controller {

    public $ADMIN_ID;

    public function __construct() {
        parent::__construct();
    }

    public function is_logged_in() {
        if (!is_null($this->ADMIN_ID) && $this->ADMIN_ID != '') {
            return TRUE;
        } else {
            $this->logout();
        }
    }

    public function logout() {
        $this->ADMIN_ID = NULL;
        redirect('Admin/index', 'refresh');
    }

}