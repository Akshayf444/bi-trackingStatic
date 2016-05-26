<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class modal_popup extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->library('Csvimport');

         //$this->load->library('csvimport');

        $this->load->model('admin_model');
        $this->load->model('Master_Model');
        $this->load->library('grocery_CRUD');

//       $this->ADMIN_ID= $this->session->set_userdata('admin_id', $validadmin['admin_id']);
    }
    public function doc_csv() {
        $data = array('title' => 'Import ', 'content' => 'admin/doctor_view', 'page_title' => 'Import csv', 'view_data' => 'blank');
        $this->load->view('template3', $data);

        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;
            while (($row = fgetcsv($fp, "500", ",")) != FALSE) {
                $count++;
                if ($count == 1) {
                    continue;
                }
                $check['show'] = $this->admin_model->emp_duplicate($row['0']);
//                var_dump($check);
//                echo $row['0'];
                if (!empty($check) && $check != '') {
                    $data = array(
                        'Account_ID' => $row['0'],
                        'Salutation' => $row['1'],
                        'First_Name' => $row['2'],
                        'Last_Name' => $row['3'],
                        'Account_Name' => $row['4'],
                        'Specialty' => $row['5'],
                        'Specialty_2' => $row['6'],
                        'Specialty_3' => $row['7'],
                        'Specialty_4' => $row['8'],
                        'Individual_Type' => $row['9'],
                        'Email' => $row['10'],
                        'Gender' => $row['11'],
                        'Mobile' => $row['12'],
                        'Status' => $row['13'],
                        'Created_Date' => $row['14'],
                        'Created_By' => $row['15'],
                        'Modified_Date' => $row['16'],
                        'Modified_By' => $row['17'],
                        'City' => $row['18'],
                        'State' => $row['19'],
                        'Pin_Code' => $row['20'],
                        'Address' => $row['21'],
                    );
//insert csv data into mysql table
                    $sql = $this->admin_model->insert_csv_doc($data);
                }
            }
        }
    }
}