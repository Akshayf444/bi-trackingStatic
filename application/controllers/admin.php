<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . 'libraries/ExcelExport.php';

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper();
        $this->load->library('Csvimport');
        $this->load->helper("url");
        $this->load->model('admin_model');
        $this->load->model('Master_Model');
        $this->load->model('User_model');
        $this->load->model('Encryption');
        $this->load->library('grocery_CRUD');
//          $this->load->library('ExcelExport');
        $this->load->model('User_model');
        $this->nextMonth = date('m');
        $this->nextYear = date('Y');
        /* if (!$this->is_logged_in('ADMIN')) {
          $this->logout();
          } */
    }

    public function index() {
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $validadmin = $this->admin_model->login($username, $password);
            if (empty($validadmin)) {
                $data['message'] = 'Username/Password Incorrect';
                $data = array('title' => 'Login', 'content' => 'admin/login', 'view_data' => $data);
                $this->load->view('template1', $data);
            } else {
                $this->session->set_userdata('admin_id', $validadmin['admin_id']);
                redirect('admin/dashboard', 'refresh');
            }
        }
        $data = array('title' => 'Login', 'content' => 'admin/login', 'view_data' => 'blank');
        $this->load->view('template1', $data);
    }

    public function dashboard() {
        $data['productlist'] = $this->admin_model->show_pro_list();
        $data['Doctor_Count'] = $this->admin_model->count();
        $data['Actual_Count'] = $this->admin_model->count_achive($this->nextMonth, $this->nextYear);
        $data['Target_Count'] = $this->admin_model->total_target($this->nextMonth, $this->nextYear);
        $data['Con_Count'] = $this->admin_model->total_convertion();

        $data = array('title' => 'Dashboard', 'content' => 'admin/dashboard', 'page_title' => 'Dashboard', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function emp_view() {
        if ($this->is_logged_in('ADMIN')) {
            $data['show'] = $this->admin_model->emp_view();
            $result = $this->admin_model->find_zone();
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
            $profile = $this->admin_model->find_profile();
            $data['profile'] = $this->Master_Model->generateDropdown($profile, 'Profile', 'Profile');
            $conditions = array();
            if ($this->input->get('id') && $this->input->get('id') != '-1') {
                $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('id'));
                $conditions[0] = "em.Zone = '" . $this->input->get('id') . "'";
            }
            if ($this->input->get('profile') && $this->input->get('profile') != '-1') {
                $data['profile'] = $this->Master_Model->generateDropdown($profile, 'Profile', 'Profile', $this->input->get('profile'));
                $conditions[1] = "em.Profile = '" . $this->input->get('profile') . "'";
            }
            $fields = array('VEEVA_Employee_ID', 'Local_Employee_ID', 'First_Name', 'Middle_Name', 'Last_Name', 'Full Name', 'Gender', 'Mobile', 'Email_ID', 'Username', 'Address_1', 'Address_2', 'City', 'State', 'Division', 'Product', 'Zone', 'Region', 'Profile', 'Designation', 'Created_By', 'Created_Date', 'Modified_By', 'Modified_date', 'Date_of_Joining', 'DOB', 'Reporting_To', 'Reporting_VEEVA_ID', 'Reporting_Local_ID', 'Status', 'Territory ID', 'Territory');
            $data['show'] = $this->admin_model->zone_data(0, 0, $conditions);
//            var_dump($data);
            $array = json_decode(json_encode($data['show']), true, JSON_NUMERIC_CHECK);

            if ($this->input->get('Export') == 'Export') {
                ExportToExcel($array, 'EmployeeMaster', $fields);
            }
            $data = array('title' => 'Employee Master', 'content' => 'admin/add_emp', 'page_title' => ' Employee Master', 'view_data' => $data);
            $this->load->view('template3', $data);
        } else {
            echo 'You Are Not Authorised to view this page';
        }
    }

    public function emp_Doc() {
        $this->load->model('User_model');
        $id = $_GET['id'];
        $condtions[0] = "em.Territory =  '" . $id . "'";
        $Territory = $this->User_model->getTerritory1($condtions);
//var_dump($Territory);
        if (!empty($Territory)) {
            $Territory = array_shift($Territory);
        }

        $title = isset($Territory->Full_Name) ? $Territory->Full_Name : "Doctor List";
        $data['show'] = $this->admin_model->emp_doc($id);
        $data = array('title' => 'Doctor List', 'content' => 'admin/emp_doc', 'page_title' => $title, 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function emp_del() {
        $id = $_GET['id'];
        $data = array('status' => 0);
        $this->admin_model->del_emp($id, $data);
        redirect('admin/emp_view', 'refresh');
    }

    public function emp_add() {
        if ($this->input->post('Territory') != 'others') {
            $terr = $this->input->post('Territory');
        } else {
            $data = array('Territory' => $this->input->post('Territorys'), 'status' => 1);
            $terr = $this->admin_model->insert_territory($data);
        }
        if ($this->input->post('State') != 'others') {
            $State = $this->input->post('State');
        } else {
            $State = $this->input->post('States');
        }
        if ($this->input->post('Region') != 'others') {
            $Region = $this->input->post('Region');
        } else {
            $Region = $this->input->post('Regions');
        }


        if ($_POST) {
            $check = $this->admin_model->emp_duplicate($this->input->post('VEEVA_Employee_ID'), $this->input->post('Email_ID'));
            if (empty($check)) {
                $data = array(
                    'VEEVA_Employee_ID' => $this->input->post('VEEVA_Employee_ID'),
                    'Local_Employee_ID' => $this->input->post('Local_Employee_ID'),
                    'First_Name' => $this->input->post('First_Name'),
                    'Middle_Name' => $this->input->post('Middle_Name'),
                    'Last_Name' => $this->input->post('Last_Name'),
                    'Full_Name' => $this->input->post('Full_Name'),
                    'Territory' => $terr,
                    'Gender' => $this->input->post('Gender'),
                    'Mobile' => $this->input->post('Mobile'),
                    'Email_ID' => $this->input->post('Email_ID'),
                    'Username' => $this->input->post('Username'),
                    'Password' => $this->Encryption->encode($this->input->post('First_Name') . '@bi'),
                    'Last_Login' => $this->input->post('Last_Login'),
                    'Address_1' => $this->input->post('Address_1'),
                    'Address_2' => $this->input->post('Address_2'),
                    'City' => $this->input->post('City'),
                    'State' => $State,
                    'Division' => $this->input->post('Division'),
                    'Product' => $this->input->post('Product'),
                    'Zone' => $this->input->post('Zone'),
                    'Region' => $Region,
                    'Profile' => $this->input->post('Profile'),
                    'Designation' => $this->input->post('Designation'),
                    'Created_By' => 'System',
                    'created_date' => date('Y-m-d'),
                    'Date_of_Joining' => $this->input->post('Date_of_Joining'),
                    'DOB' => $this->input->post('DOB'),
                    'Reporting_To' => $this->input->post('Reporting_To'),
                    'Reporting_VEEVA_ID' => $this->input->post('Reporting_VEEVA_ID'),
                    'Reporting_Local_ID' => $this->input->post('Reporting_Local_ID'),
                    'Status' => '1',
                );
                $this->admin_model->insert($data);
                $name = $this->input->post('Full_Name');

                $pass = $this->input->post('First_Name') . '@bi';
                $user = $this->input->post('Email_ID');


                $message = 'Added New ' . $this->input->post('Profile') . ' ' . $this->input->post('Full_Name') . '[' . $this->input->post('VEEVA_Employee_ID') . '] ';
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => $message,
                    'VEEVA_Employee_ID' => 'Admin',
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => 'ADMIN',
                );
                $this->User_model->insertLog($logdata);
                $this->User_model->PasswordMail(array('VEEVA_Employee_ID' => $this->input->post('VEEVA_Employee_ID'), 'created_at' => date('Y-m-d H:i:s')));
                redirect('admin/emp_view', 'refresh');
            } else {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Employee Already Exist', 'danger'));
            }
        }
        $result = $this->admin_model->find_Designation();
        $data['Designation'] = $this->Master_Model->generateDropdown($result, 'Designation', 'Designation');
        $result = $this->admin_model->find_territory();
        $data['Territory'] = $this->Master_Model->generateDropdown($result, 'ID', 'Territory', 'Territory');
        $result = $this->admin_model->find_Division();
        $data['Division'] = $this->Master_Model->generateDropdown($result, 'Division', 'Division');
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $result = $this->admin_model->find_region();
        $data['region'] = $this->Master_Model->generateDropdown($result, 'Region', 'Region');
        $result = $this->admin_model->reporting_to($this->input->post('Profile'));
        $data['Reporting_To'] = $this->Master_Model->generateDropdown($result, 'Reporting_To', 'Reporting_To');
        $result = $this->admin_model->find_Profile();
        $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile');
        $result = $this->admin_model->find_state();
        $data['State'] = $this->Master_Model->generateDropdown($result, 'State', 'State');
        $data['Reporting_id'] = $this->admin_model->reporting_id($this->input->post('Reporting_To'));
        $data = array('title' => 'Add Employee', 'content' => 'admin/emp_add', 'page_title' => 'Add Employee', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function emp_csv() {
        $this->load->model('User_model');
        $errors = array();
        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;
            $properFormat = TRUE;
            $veevaids = $this->admin_model->selectDistinctVeevaIDS();

            while (($row = fgetcsv($fp, "500", ",")) != FALSE && $properFormat == TRUE) {
//                echo $properFormat;
                if ($row['0'] != '') {
                    if ($count == 0) {
                        if (count($row) != 25) {
                            $properFormat = FALSE;
                        }
                    }
                    $count++;
                    if ($count == 1) {
                        continue;
                    }
                    $terr = '';
                    $territory = $this->User_model->getTerritory2(array("t.Territory = '" . $row['6'] . "'"));
                    
                    ///Escaping the characters
                    for ($index = 0; $index < 25; $index++) {
                        $row[$index] = mysql_real_escape_string($row[$index]);
                    }

                    if (!empty($territory)) {
                        $territory = array_shift($territory);
                        $terr = $territory->id;
                        $data = array(
                            'VEEVA_Employee_ID' => $row['0'],
                            'Local_Employee_ID' => $row['1'],
                            'First_Name' => $row['2'],
                            'Middle_Name' => $row['3'],
                            'Last_Name' => $row['4'],
                            'Full_Name' => $row['5'],
                            'Territory' => $terr,
                            'Gender' => $row['7'],
                            'Mobile' => $row['8'],
                            'Email_ID' => $row['9'],
                            'Username' => $row['10'],
                            'Password' => $this->Encryption->encode($row['2'] . '@bi'),
                            'Last_Login' => '',
                            'Address_1' => $row['11'],
                            'Address_2' => $row['12'],
                            'City' => $row['13'],
                            'State' => $row['14'],
                            'Division' => $row['15'],
                            'Product' => '',
                            'Zone' => $row['16'],
                            'Region' => $row['17'],
                            'Profile' => $row['18'],
                            'Designation' => $row['19'],
                            'Created_By' => 'System',
                            'Created_Date' => date('Y-m-d'),
                            'Modified_By' => '',
                            'Modified_Date' => '',
                            'Date_of_Joining' => $row['20'],
                            'DOB' => $row['21'],
                            'Reporting_To' => $row['22'],
                            'Reporting_VEEVA_ID' => $row['23'],
                            'Reporting_Local_ID' => $row['24'],
                            'Status' => '1',
                            'password_status' => '',
                        );
                        if (!in_array($row[0], $veevaids) && !in_array($row[10], $veevaids)) {


                            $this->admin_model->insert_csv($data);
                            $message = 'Added New ' . $row['18'] . ' ' . $row['5'] . '[' . $row['0'] . '] ';
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'ADMIN',
                            );
                            $this->User_model->insertLog($logdata);
                            $this->User_model->PasswordMail(array('VEEVA_Employee_ID' => $row['0'], 'created_at' => date('Y-m-d H:i:s')));
                        } else {
                            unset($data['Password']);
                            unset($data['password_status']);
                            unset($data['Username']);
                            unset($data['Last_Login']);
                            unset($data['Mobile']);
                            unset($data['DOB']);
                            $this->db->where(array('VEEVA_Employee_ID' => $row['0']));
                            $this->db->update('Employee_Master', $data);
                        }
                    } else {
                        array_push($errors, 'Terriotory ' . $row['6'] . ' Does Not Exist Territory Master .Please Add Territory First</b>');
                    }
                }
            }

            if (!empty($errors)) {
                $this->session->set_userdata('message', '<div class="col-lg-12 alert alert-danger">' . join(".", $errors) . '</div>');
            }

            if ($properFormat == FALSE) {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Upload Excel File is not in proper format. Please download Sample File For Uploading', 'danger'));
            }
        }
        redirect('admin/emp_view', 'refresh');
    }

    public function bdm_wise() {
        $data['show'] = $this->admin_model->BDM_show();

        $data = array('title' => 'Employee View', 'content' => 'admin/bdm_wise', 'page_title' => 'Employee Master', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function territory_view() {
        $data['show'] = $this->admin_model->territory();
        $data = array('title' => ' Territory View', 'content' => 'admin/territory', 'page_title' => 'Territory Master', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function territory_add() {
        if ($_POST) {
            $name = $this->input->post('territory');
            $names = implode('-', $name);
            $vaild = $this->admin_model->territory_view($names);

            if (empty($vaild)) {
                $data = array('Territory' => $names, 'status' => 1);
                $this->admin_model->insert_territory($data);

                $message = 'Added New ' . $names;
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => $message,
                    'VEEVA_Employee_ID' => 'Admin',
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => 'ADMIN',
                );
                $this->User_model->insertLog($logdata);
                redirect('admin/territory_view', 'refresh');
            } else {
                redirect('admin/territory_view', 'refresh');
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Already Exit', 'error'));
            }
        }
    }

    public function update_terr() {
        $id = $_GET['id'];
        $data['rows'] = $this->admin_model->find_by_terrid($id);

        if ($this->input->post()) {
            $terrid = $this->input->post('terrid');
            $name = $this->input->post('territory');
            $names = implode('-', $name);
            $data = array(
                'territory' => $names,
                'status' => '1',
            );
            $this->admin_model->update_terr($terrid, $data);
            $message = 'Update ' . $names;
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => 'Admin',
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ADMIN',
            );
            $this->User_model->insertLog($logdata);

            redirect('admin/territory_view', 'refresh');
        }

        $data = array('title' => 'Upadte Activity', 'content' => 'admin/edit_terr', 'page_title' => 'Update Territory', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function terr_del() {
        $id = $_GET['id'];
        $data = array('status' => 0);
        $this->admin_model->del_terr($id, $data);

        redirect('admin/territory_view', 'refresh');
        $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Delete Successfully', 'success'));
    }

    public function ajax_data() {
        $array = array('veevaid' => 'Reporting_VEEVA_ID', 'localid' => 'Reporting_Local_ID');
        $result = $this->admin_model->reporting_to($this->input->post('profile'));
        $data = $this->Master_Model->generateDropdown($result, 'Reporting_To', 'Reporting_To', 0, $array);
        echo $data;
    }

    public function update_emp() {
        $this->load->model('User_model');
        $id = $_GET['id'];
        $data['rows'] = $this->admin_model->find_by_empid($id);
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $data['rows']['Zone']);
        $result = $this->admin_model->find_region();
        $data['region'] = $this->Master_Model->generateDropdown($result, 'Region', 'Region', $data['rows']['Region']);
        $result = $this->admin_model->find_territory();
        $data['Territory'] = $this->Master_Model->generateDropdown($result, 'ID', 'Territory', $data['rows']['Territory']);
        $result = $this->admin_model->find_Designation();
        $data['Designation'] = $this->Master_Model->generateDropdown($result, 'Designation', 'Designation', $data['rows']['Designation']);
        $result = $this->admin_model->find_Division();
        $data['Division'] = $this->Master_Model->generateDropdown($result, 'Division', 'Division', $data['rows']['Division']);
        $result = $this->admin_model->find_Profile();
        $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile', $data['rows']['Profile']);
        $result = $this->admin_model->find_REPORTING_TO();
        $data['Reporting_To'] = $this->Master_Model->generateDropdown($result, 'Reporting_To', 'Reporting_To', $data['rows']['Reporting_To']);
        $result = $this->admin_model->find_state();
        $data['State'] = $this->Master_Model->generateDropdown($result, 'State', 'State', $data['rows']['State']);

        if ($this->input->post('Territory') != 'others') {
            $terr = $this->input->post('Territory');
        } else {
            $data = array('Territory' => $this->input->post('Territorys'), 'status' => 1);
            $terr = $this->admin_model->insert_territory($data);
        }
        if ($this->input->post('State') != 'others') {
            $State = $this->input->post('State');
        } else {
            $State = $this->input->post('States');
        }
        if ($this->input->post('Region') != 'others') {
            $Region = $this->input->post('Region');
        } else {
            $Region = $this->input->post('Regions');
        }
        $check = $this->admin_model->emp_duplicate($this->input->post('VEEVA_Employee_ID'), $this->input->post('Email_ID'));

        if ($this->input->post()) {
            $empid = $this->input->post('VEEVA_Employee_ID');

            $data = array(
                'VEEVA_Employee_ID' => $this->input->post('VEEVA_Employee_ID'),
                'Local_Employee_ID' => $this->input->post('Local_Employee_ID'),
                'First_Name' => $this->input->post('First_Name'),
                'Middle_Name' => $this->input->post('Middle_Name'),
                'Last_Name' => $this->input->post('Last_Name'),
                'Full_Name' => $this->input->post('Full_Name'),
                'Territory' => $terr,
                'Gender' => $this->input->post('Gender'),
                'Mobile' => $this->input->post('Mobile'),
                'Email_ID' => $this->input->post('Email_ID'),
                'Username' => $this->input->post('Username'),
                'Address_1' => $this->input->post('Address_1'),
                'Address_2' => $this->input->post('Address_2'),
                'City' => $this->input->post('City'),
                'State' => $State,
                'Division' => $this->input->post('Division'),
                'Product' => $this->input->post('Product'),
                'Zone' => $this->input->post('Zone'),
                'Region' => $Region,
                'Profile' => $this->input->post('Profile'),
                'Designation' => $this->input->post('Designation'),
                'Modified_By' => 'Admin',
                'Modified_Date' => date('Y-m-d'),
                'Date_of_Joining' => $this->input->post('Date_of_Joining'),
                'DOB' => $this->input->post('DOB'),
                'Reporting_To' => $this->input->post('Reporting_To'),
                'Reporting_VEEVA_ID' => $this->input->post('Reporting_VEEVA_ID'),
                'Reporting_Local_ID' => $this->input->post('Reporting_Local_ID'),
                'Status' => '1',
            );
            $this->admin_model->update_emp($empid, $data);
            $message = 'Updated ' . $this->input->post('Profile') . ' ' . $this->input->post('Full_Name') . '[' . $this->input->post('VEEVA_Employee_ID') . '] ';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => 'Admin',
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ADMIN',
            );
            $this->User_model->insertLog($logdata);
            redirect('admin/emp_view', 'refresh');
        }



        $data = array('title' => 'Update Employee', 'content' => 'admin/update_emp', 'page_title' => 'Update Employee', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function get_record() {
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->admin_model->find_REPORTING_TO_VALUE($name)));
    }

    public function empdoc_del() {
        $id = $_GET['id'];
        $id1 = $_GET['emp_id'];
//        $data = array('status' => 'InActive');
//        $this->admin_model->del_docmaster($id, $data);
        $this->admin_model->del_empdoc($id1, $id);
        redirect('admin/emp_docmaster', 'refresh');
    }

    public function edit() {
        $id = $_GET['id'];
        $name = $_GET['name'];
        if ($this->input->post()) {
            if ($name == 'asm') {
                $check['show'] = $this->admin_model->asm_edit($id);
            } elseif ($name == 'zsm') {
                $check['show'] = $this->admin_model->zsm_edit($id);
            } elseif ($name == 'bdm') {
                $check['show'] = $this->admin_model->bdm_edit($id);
            }
        }
        $check['show'] = $this->admin_model->asm_by_id($id);
        $data = array('title' => 'Login', 'content' => 'admin/edit', 'view_data' => $check);
        $this->load->view('template2', $data);
    }

    public function manage() {
        if ($this->input->post()) {
            $team = $this->input->post('team');
            if ($team == 'asm') {
                $check['team1'] = $this->admin_model->asm();
            } elseif ($team == 'zsm') {
                $check['team2'] = $this->admin_model->zsm();
            } elseif ($team == 'bdm') {
                $check['team3'] = $this->admin_model->bdm();
            } else {
                $check['team4'] = '';
            }
        }

        $data = array('title' => 'Login', 'content' => 'admin/Manage', 'view_data' => $check);
        $this->load->view('template2', $data);
    }

    public function view_activity() {
        $data['show'] = $this->admin_model->view_activity();
        $data = array('title' => 'View_Activity', 'content' => 'admin/activity_view', 'page_title' => 'Activity Master', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function act_del() {
        $id = $_GET['id'];
        $data = array('status' => 1);
        $this->admin_model->del_act($id, $data);
        redirect('admin/view_activity', 'refresh');
    }

    public function add_activity() {
        if ($_POST) {
            $data = array(
                'Activity_Name' => $this->input->post('Activity_Name'),
                'Division' => $this->input->post('Division'),
                'Product_ID' => $this->input->post('Product_ID'),
                'Created_By' => 'Admin',
                'Status' => '0',
                'created_at' => date('Y-m-d'),
            );
            $this->admin_model->insert_activity($data);
            redirect('admin/view_activity', 'refresh');
        }
        $result = $this->admin_model->show_pro_list();
        $data['Product'] = $this->Master_Model->generateDropdown($result, 'id', 'Brand_Name');
        $data = array('title' => 'Login', 'content' => 'admin/add_activity', 'page_title' => 'Add Activity', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function update_act() {
        if ($this->input->get('id')) {
            $id = $_GET['id'];
            $data['rows'] = $this->admin_model->find_by_activityid($id);

            $result = $this->admin_model->show_pro_list();
            $data['Product'] = $this->Master_Model->generateDropdown($result, 'id', 'Brand_Name', $data['rows']['Product_Id']);
        }

        if ($this->input->post()) {
            $actid = $this->input->post('Act_id');
            $data = array(
                'Activity_Name' => $this->input->post('Activity_Name'),
                'Division' => $this->input->post('Division'),
                'Product_ID' => $this->input->post('Product_ID'),
                'modified_by' => 'Admin',
                'Status' => 0,
                'updated_at' => date('Y-m-d'),
            );
            $this->admin_model->update_act($actid, $data);
            redirect('admin/view_activity', 'refresh');
        }

        $data = array('title' => 'Upadte Activity', 'content' => 'admin/update_activity', 'page_title' => 'Update Activity', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function profile_view() {
        if (isset($_POST['tab1'])) {
            $this->load->admin_model->Tab1();
            redirect('admin/profile_view', 'refresh');
        }
        $data['show'] = $this->admin_model->view_profile_controller();

        $data = array('title' => 'Control Access', 'content' => 'admin/profile_controller', 'page_title' => 'Control Access', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function profile_active() {
        if ($this->input->post()) {
            $actid = $this->input->post('Act_id');
            $data = array(
                'Tab1' => $this->input->post('Tab1'),
                'Tab2' => $this->input->post('Tab2'),
                'Tab3' => $this->input->post('Tab3'),
                'Tab4' => $this->input->post('Tab4'),
                'Tab5' => $this->input->post('Tab5'),
                'updated_at' => date('Y-m-d'),
            );
            $this->admin_model->active_profile($actid, $data);
            redirect('admin/view_activity', 'refresh');
        }
    }

    public function doc_view() {
        require_once( APPPATH . 'libraries/Autopaginate.php' );
        $page = isset($_GET['page']) && !empty($_GET['page']) ? (int) $_GET['page'] : 1;

        $per_page = 500;
        $HTML = "";
        $total_count = $this->admin_model->doc_count();
        $pagination = new Autopaginate($page, $per_page, $total_count->Account_Id);
        $HTML = $pagination->renderPaging('doc_view', $page);
        $data['html'] = $HTML;
        $data["show"] = $this->admin_model->doc_view($per_page, $pagination->offset());
        $data = array('title' => 'Doctor', 'content' => 'admin/doctor_view', 'page_title' => 'Doctor Master', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function GroceryCrud() {
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme('twitter-bootstrap');
            $crud->set_table('Employee_Master');
            $crud->set_subject('Employee');
            $crud->set_primary_key('VEEVA_Employee_ID', 'Employee_Master');
            $output = $crud->render();
            $data['output'] = $output->output;
            $data['css_files'] = $output->css_files;
            $data['js_files'] = $output->js_files;
            $data = array('title' => 'Profile_Completion', 'content' => 'admin/GroceryCrud', 'view_data' => $data);
            $this->load->view('template3', $data);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function QuestionMaster() {
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme('flexigrid');
            $crud->set_table('Question_Master');
            $crud->set_subject('Question');
            $output = $crud->render();
            $data['output'] = $output->output;
            $data['css_files'] = $output->css_files;
            $data['js_files'] = $output->js_files;
            $data = array('title' => 'Profile_Completion', 'content' => 'admin/GroceryCrud', 'page_title' => 'Question Master', 'view_data' => $data);
            $this->load->view('template3', $data);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function BrandMaster() {
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme('flexigrid');
            $crud->set_table('Brand_Master');
            $crud->set_subject('Product');
            $output = $crud->render();
            $data['output'] = $output->output;
            $data['css_files'] = $output->css_files;
            $data['js_files'] = $output->js_files;
            $data = array('title' => 'Profile_Completion', 'content' => 'admin/GroceryCrud', 'page_title' => 'Question Master', 'view_data' => $data);
            $this->load->view('template3', $data);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function doc_csv() {
        $values = array();
        $this->load->model('User_model');
        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;

            $properFormat = TRUE;
            $sql = "INSERT INTO Doctor_Master(Account_ID,Salutation,First_Name,Last_Name,Account_Name,Record_Type,Specialty,Specialty_2,Specialty_3,Specialty_4,Individual_Type,Email,Gender,Mobile,Status,Created_Date,Created_By,Modified_Date,Modified_By,City,State,Pin_Code,Address) VALUES ";
            while (($row = fgetcsv($fp, "500", ",")) != FALSE && $properFormat == TRUE) {
//                echo $properFormat;
                if ($row['0'] != '') {
                    if ($count == 0) {
                        if (count($row) != 18) {
                            $properFormat = FALSE;
                        }
                    }
                    $count++;
                    
                    ///Escaping the characters
                    for ($index = 0; $index < 18; $index++) {
                        $row[$index] = mysql_real_escape_string($row[$index]);
                    }

                    if ($count == 1) {
                        continue;
                    }

                    $data = array(
                        'Account_ID' => $row['0'],
                        'Salutation' => $row['1'],
                        'First_Name' => $row['2'],
                        'Last_Name' => $row['3'],
                        'Account_Name' => $row['4'],
                        'Record_Type' => $row['5'],
                        'Specialty' => $row['6'],
                        'Specialty_2' => $row['7'],
                        'Specialty_3' => $row['8'],
                        'Specialty_4' => $row['9'],
                        'Individual_Type' => $row['10'],
                        'Email' => $row['11'],
                        'Gender' => $row['12'],
                        'Mobile' => $row['13'],
                        'Status' => 'Active',
                        'Created_Date' => date('Y-m-d'),
                        'Created_By' => 'System',
                        'Modified_Date' => '',
                        'Modified_By' => '',
                        'City' => $row['14'],
                        'State' => $row['15'],
                        'Pin_Code' => $row['16'],
                        'Address' => $row['17']
                    );

                    $query = "('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$row[5]','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$row[12]','$row[13]','Active'," . date('Y-m-d') . ",'System','','','$row[14]','$row[15]','$row[16]','$row[17]')";
                    array_push($values, $query);
                    //}
                }
            }

            $sql.=join(",", $values);
            $sql.=" ON Duplicate KEY UPDATE Account_Name = VALUES(Account_Name),State = VALUES(State),Pin_Code = VALUES(Pin_Code),Individual_Type = VALUES(Individual_Type)";
            $this->db->query($sql);

            if ($properFormat == FALSE) {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Upload Excel File is not in proper format. Please download Sample File For Uploading', 'danger'));
            }
        }

        redirect('admin/doc_view', 'refresh');
    }

    public function add_doc() {

        if ($_POST) {
            $check = $this->admin_model->doc_duplicate($this->input->post('Account_ID'));
            if (empty($check)) {
                $data = array(
                    'Account_ID' => $this->input->post('Account_ID'),
                    'Salutation' => $this->input->post('Salutation'),
                    'First_Name' => $this->input->post('First_Name'),
                    'Last_Name' => $this->input->post('Last_Name'),
                    'Account_Name' => $this->input->post('Account_Name'),
                    'Record_Type' => $this->input->post('Record_Type'),
                    'Specialty' => $this->input->post('Specialty'),
                    'Specialty_2' => $this->input->post('Specialty2'),
                    'Specialty_3' => $this->input->post('Specialty3'),
                    'Specialty_4' => $this->input->post('Specialty4'),
                    'Individual_Type' => $this->input->post('Individual_Type'),
                    'Email' => $this->input->post('Email_ID'),
                    'Gender' => $this->input->post('Gender'),
                    'Mobile' => $this->input->post('Mobile'),
                    'Status' => 'Active',
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => 'System',
                    'Modified_Date' => '',
                    'Modified_By' => '',
                    'City' => $this->input->post('City'),
                    'State' => $this->input->post('State'),
                    'Pin_Code' => $this->input->post('Pincode'),
                    'Address' => $this->input->post('Address')
                );
                $this->admin_model->insert_doc($data);
                $message = 'Add New Doctor ' . $this->input->post('Profile') . ' ' . $this->input->post('Account_Name') . '[' . $this->input->post('Account_ID') . '] ';
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => $message,
                    'VEEVA_Employee_ID' => 'Admin',
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => 'ADMIN',
                );
                $this->User_model->insertLog($logdata);
                redirect('admin/doc_view', 'refresh');
            }
        }
        $data = array('title' => 'Login', 'content' => 'admin/add_doc', 'page_title' => 'Add Doctor', 'view_data' => 'blank');
        $this->load->view('template3', $data);
    }

    public function doc_del() {
        $id = $_GET['id'];
        $data = array('status' => 'inactive');
        $this->admin_model->del_doc($id, $data);
        redirect('admin/doc_view', 'refresh');
    }

    public function update_doc() {
        $id = $_GET['id'];
        $data['rows'] = $this->admin_model->find_by_docid($id);
        $result = $this->admin_model->find_specialty();
        $data['specialty'] = $this->Master_Model->generateDropdown($result, 'Specialty', 'Specialty', $data['rows']['Specialty']);
        $result = $this->admin_model->find_type();
        $data['IndividualType'] = $this->Master_Model->generateDropdown($result, 'Individual_Type', 'Individual_Type', $data['rows']['Individual_Type']);

        if ($this->input->post()) {
            $account_id = $this->input->post('Account_ID');
            $data = array(
                'Salutation' => $this->input->post('Salutation'),
                'First_Name' => $this->input->post('First_Name'),
                'Last_Name' => $this->input->post('Last_Name'),
                'Account_Name' => $this->input->post('Account_Name'),
                'Record_Type' => $this->input->post('Record_Type'),
                'Specialty' => $this->input->post('Specialty'),
                'Specialty_2' => $this->input->post('Specialty2'),
                'Specialty_3' => $this->input->post('Specialty3'),
                'Specialty_4' => $this->input->post('Specialty4'),
                'Individual_Type' => $this->input->post('Individual_Type'),
                'Email' => $this->input->post('Email_ID'),
                'Gender' => $this->input->post('Gender'),
                'Mobile' => $this->input->post('Mobile'),
                'Status' => $this->input->post('Status'),
                'Created_Date' => $this->input->post('Activity_Name'),
                'Created_By' => $this->input->post('Activity_Name'),
                'Modified_Date' => $this->input->post('Activity_Name'),
                'Modified_By' => $this->input->post('Activity_Name'),
                'City' => $this->input->post('City'),
                'State' => $this->input->post('State'),
                'Pin_Code' => $this->input->post('Pincode'),
                'Address' => $this->input->post('Address'),
            );

            $this->admin_model->update_doc($account_id, $data);
            $this->admin_model->insert_doc($data);
            $message = 'Update Doctor ' . $this->input->post('Profile') . ' ' . $this->input->post('Account_Name') . '[' . $this->input->post('Account_ID') . '] ';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => 'Admin',
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ADMIN',
            );
            $this->User_model->insertLog($logdata);
            redirect('admin/doc_view', 'refresh');
        }

        $data = array('title' => 'Upadte Activity', 'content' => 'admin/edit_doc', 'page_title' => 'Update Doctor', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function control_access() {
        
    }

    public function empdoc_csv() {
        $this->load->model('User_model');
        $values = array();
        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;
            $properFormat = TRUE;
            $sql = "INSERT INTO Employee_Doc(VEEVA_Employee_ID,Local_Employee_ID,Employee_Name,VEEVA_Account_ID,Account_Name,SAP_ID,Territory,CompositeKey,Status) VALUES ";

            while (($row = fgetcsv($fp, "500", ",")) != FALSE && $properFormat == TRUE) {

                if ($row['0'] != '') {
                    if ($count == 0) {
                        if (count($row) != 7) {
                            $properFormat = FALSE;
                        }
                    }
                    $count++;
                    
                    ///Escaping the characters
                    for ($index = 0; $index < 7; $index++) {
                        $row[$index] = mysql_real_escape_string($row[$index]);
                    }

                    if ($count == 1) {
                        continue;
                    }
                    $terr = $row[6];

                    //$territory = $this->User_model->getTerritory2(array("t.Territory = '" . $row['6'] . "'"));
                    //var_dump($territory);
                    /* if (!empty($territory)) {
                      $territory = array_shift($territory);
                      $terr = $territory->id;
                      } else {
                      $terr = $this->admin_model->addTerritory(array('Territory' => $row['6']));
                      } */

                    $query = "('$row[1]','$row[0]','$row[2]','$row[3]','$row[4]','$row[5]','$terr','$row[1]" . "$row[3]',1)";
                    array_push($values, $query);
                } else {
                    $properFormat = FALSE;
                }
            }

            $sql.=join(",", $values);
            $sql.=" ON Duplicate KEY UPDATE Account_Name = VALUES(Account_Name),Employee_Name = VALUES(Employee_Name),Status = VALUES(Status) ";
            //echo $sql;
            if ($properFormat == FALSE) {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Uploaded Excel File is not in proper format. Please download Sample File For Uploading', 'danger'));
            } else {
                $this->db->query($sql);
            }
        }
        redirect('admin/emp_docmaster', 'refresh');
    }

    public function hospital_csv() {
        $data = array('title' => 'View_Employee', 'content' => 'admin/emp_csv', 'page_title' => 'Import FILES', 'view_data' => 'blank');
        $this->load->view('template3', $data);
        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;
            while (($row = fgetcsv($fp, "500", ",")) != FALSE) {
                $count++;
                if ($count == 1) {
                    continue;
                }
                $data = array(
                    'NEWS_region' => $row['1'],
                    'VEEVA_Employee_ID' => $row['2'],
                    'Employee_Name' => $row['3'],
                    'VEEVA_Account_ID' => $row['4'],
                    'Account_Name' => $row['5'],
                    'SAP_ID' => $row['10'],
                    'Status' => 'Active',
                    'Specialty' => $row['7'],
                );

                $sql = $this->admin_model->insert_hospital($data);
            }
        }
    }

    public function tab_csv() {
        $data = array('title' => 'View_Employee', 'content' => 'admin/emp_csv', 'page_title' => 'Import FILES', 'view_data' => 'blank');
        $this->load->view('template3', $data);
        if ($this->input->post()) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r+');
            $count = 0;
            while (($row = fgetcsv($fp, "500", ",")) != FALSE) {
                $count++;
                if ($count == 1) {
                    continue;
                }
                $data = array(
                    'VEEVA_Employee_ID' => $row['0'],
                );

                $sql = $this->admin_model->insert_tab($data);
            }
        }
    }

    public function UnlockUser() {
        $data['unlock'] = $this->admin_model->bdm_unlocked_list();

        $data = array('title' => 'Block Accounts', 'content' => 'admin/bdm_unlocked', 'page_title' => 'Block Accounts', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function unlock_account() {
        $id = $_GET['id'];
        $this->db->where('VEEVA_Employee_ID', $id);
        $this->db->delete('password_count');
        $data = array('status' => 1);
        $this->admin_model->del_emp($id, $data);
        redirect('admin/UnlockUser', 'refresh');
    }

    public function asm_target() {
        $data['show'] = $this->admin_model->target_view();

        $data = array('title' => 'Employee View', 'content' => 'admin/target_view', 'page_title' => 'ASM List', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function asm_target_by_bdm() {
        $id = $_GET['id'];
        $check = $this->admin_model->ASM_division($id);

        $data['show'] = $this->admin_model->ASM_division($id);
        if (!empty($check)) {
            if ($data['show'] == 'Diabetes') {
                $data['table'] = $this->admin_model->ASm_view($id);
                $data['ck'] = "Diabetes";
            } else {
                $data['table'] = $this->admin_model->ASm_view($id);
                $data['ck'] = "Thrombi";
            }
        }

        $data = array('title' => 'Employee View', 'content' => 'admin/target_bdm', 'page_title' => 'Target ', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function Reset_Target() {

        $veevaids = $this->input->post('VEEVA_Employee_ID');
        if (!empty($veevaids)) {
            for ($i = 0; $i < count($veevaids); $i++) {
                $id = $veevaids[$i];
                $month = $this->nextMonth;
                $year = $this->nextYear;
                $data = array('status' => 'Draft');
                $this->admin_model->reset_target($id, $month, $year, $data);
            }
        }
        //$id = $_GET['id'];
        redirect('admin/asm_target', 'refresh');
    }

    public function login_history() {
        $data['show'] = $this->admin_model->login_history();
        $data = array('title' => 'Login_History', 'content' => 'admin/login_history', 'page_title' => 'Login History ', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function reporting_change() {
        $data[] = array();

        if ($this->input->get()) {
            $id = $this->input->get('id');
            $data['rows'] = $this->admin_model->find_by_empid($id);
            $data['show'] = $this->admin_model->reporting_view2($id);
            $result = $this->admin_model->find_Profile();
            $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile', $data['rows'] ['Profile']);
            $result = $this->admin_model->find_REPORTING_TO();
            $data['Reporting_To'] = $this->Master_Model->generateDropdown($result, 'Reporting_To', 'Reporting_To', $data['rows'] ['Reporting_To']);
        }
        $data = array('title' => ' Reporting', 'content' => 'admin/reporting_change', 'page_title' => 'Reporting Change', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function change_profile() {
        $id = $this->input->post('veeva_id');
        $profile = $this->input->post('Profile');
        $Reporting_To = $this->input->post('Reporting_To');
        $reporting_id = $this->input->post('reporting_veeva_id');
        if ($this->input->post()) {
            $data = array('Profile' => $profile,
                'Reporting_To' => $Reporting_To,
                'Reporting_VEEVA_ID' => $reporting_id,
                'Designation' => $Reporting_To);
            $this->admin_model->reporting_change($id, $data);

//            redirect('admin/assign_emp', 'refresh');
        }
        $view = $this->admin_model->find_by_empid($id);


        if ($view['Profile'] == 'ASM') {
            $data['bdm'] = $this->admin_model->assign_bdm();
            if ($this->input->post()) {
                $data = array(
                    'Reporting_To' => $Reporting_To,
                    'Reporting_VEEVA_ID' => $id,
                );
                $this->admin_model->reporting_change($id, $data);

//            redirect('admin/assign_emp', 'refresh');
            }
            $data = array('title' => 'Assign_BDM', 'content' => 'admin/asign_bdm', 'page_title' => 'Assign_BDM', 'view_data' => $data);
            $this->load->view('template3', $data);
        } else {
            if ($view['Profile'] == 'ZSM') {
                $data['asm'] = $this->admin_model->assign_asm();
                $data = array('title' => 'Assign_ASM', 'content' => 'admin/assign_asm', 'page_title' => 'Assign_ASM', 'view_data' => $data);
                $this->load->view('template3', $data);
            }
        }
    }

    public function Target_assign() {
        $data['show'] = $this->admin_model->target_assign();

        $data = array('title' => 'Target_Assign', 'content' => 'admin/target_assign', 'page_title' => 'Target_Assign', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function dashboardTab() {
        if ($this->input->post('Product_Id')) {
            $Product_id = $this->input->post('Product_Id');
            $noofdoctors = 0;
            $target = 0;
            $planned = 0;

            $result = $this->admin_model->adminDashboardCount2($Product_id, $this->nextMonth, $this->nextYear);
            if (!empty($result)) {
                foreach ($result as $value) {
                    $target+= $value->Target_New_Rxn_for_the_month;
                    $planned+= $value->Planned_New_Rxn;
                    $noofdoctors+= $value->No_of_Doctors;
                }
            }
            ?>
            <div id="<?php echo $Product_id ?>" class="tab-pane fade in active">
                <div class="row" style="margin-top:5px">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-user-md"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Doctors </span>
                                <span class="info-box-number"><?php echo $noofdoctors; ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Target</span>
                                <span class="info-box-number"><?php
                                    echo $target;
                                    ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Planned</span>
                                <span class="info-box-number"><?php
                                    echo $planned;
                                    ?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>

            <?php
        }
    }

    public function Userlog() {
        $this->load->model('User_model');
        $result = $this->admin_model->find_profile();
        $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile');
        if ($this->input->post()) {
            $condtions = array(
                " WHERE em.Profile = '" . $this->input->post('Profile') . "' ",
                " AND l.description <> '' "
            );
            if ($this->input->post('Start_date') != '' && $this->input->post('End_date') != '') {
                $string = " AND DATE_FORMAT(l.Date,'%Y-%m-%d') BETWEEN '" . $this->input->post('Start_date') . "' AND '" . $this->input->post('End_date') . "' ";
                array_push($condtions, $string);
            }

            $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile', $this->input->post('Profile'));
            $data['logs'] = $this->User_model->getLog($condtions);
        }
        $data = array('title' => 'User Log', 'content' => 'admin/Userlog', 'page_title' => 'User Log', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function TabControl() {
        $this->load->model('User_model');
        $conditions = array(
            "WHERE em.Profile = 'BDM' "
        );
        $data = array();
        $data['List'] = $this->User_model->getTabcontrol($conditions);
        if ($this->input->post()) {
            $Employee_ID = $this->input->post('VEEVA_Employee_ID');
            for ($i = 0; $i < count($this->input->post('VEEVA_Employee_ID')); $i++) {
                $VEEVA_Employee_ID = $Employee_ID[$i];
                $field_array = array(
                    'VEEVA_Employee_ID' => $VEEVA_Employee_ID,
                    'Tab1' => $this->input->post($VEEVA_Employee_ID . 'Tab1'),
                    'Tab2' => $this->input->post($VEEVA_Employee_ID . 'Tab2'),
                    'Tab3' => $this->input->post($VEEVA_Employee_ID . 'Tab3'),
                    'Tab4' => $this->input->post($VEEVA_Employee_ID . 'Tab4'),
                    'Tab5' => 1
                );
                if ($this->input->post('ProfileExist' . $VEEVA_Employee_ID) == '1') {
                    $field_array['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID));
                    $this->db->update('Tab_Control', $field_array);
                } else {
                    $field_array['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('Tab_Control', $field_array);
                }
            }
            redirect('Admin/TabControl', 'refresh');
        }
        $data = array('title' => 'Tab Control', 'content' => 'admin/profile_controller', 'page_title' => 'Tab Control', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function reportsfilter() {
        $result = $this->admin_model->find_territory();
        $data['Territory'] = $this->Master_Model->generateDropdown($result, 'id', 'Territory');
        $result = $this->admin_model->reports_filter_division();
        $data['Division'] = $this->Master_Model->generateDropdown($result, 'Division', 'Division');
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $result = $this->admin_model->find_region();
        $data['region'] = $this->Master_Model->generateDropdown($result, 'Region', 'Region');
        $result = $this->admin_model->reports_filter_product();
        $data['product'] = $this->Master_Model->generateDropdown($result, 'Brand_Name', 'Brand_Name');
        if ($this->input->post()) {
            $zone = $this->input->post('zone');
            $region = $this->input->post('region');
            $terr = $this->input->post('territory');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $division = $this->input->post('division');
            $product = $this->input->post('product');
            $data['show'] = $this->admin_model->find_filter_data($zone, $region, $terr, $from, $to, $division, $product);
        }

        $data = array('title' => ' Reporting', 'content' => 'admin/reports', 'page_title' => 'Reports', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function assign_reporting_to() {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $profile = $_GET['type'];
        $view = $this->admin_model->find_by_empid($id);
        $zone = $view['Zone'];
        $division = $view['Division'];
        $data['bdm'] = $this->admin_model->assign_bdm($profile, $zone, $division);
        $data['view'] = $this->admin_model->find_by_empid($id);


        if ($this->input->post()) {

            for ($i = 0; $i < count($this->input->post('veeva_id')); $i++) {
                $veeva_id = $this->input->post('veeva_id');
                $local = $this->input->post('local_id');
                //var_dump($local);
                $data = array(
                    'Reporting_To' => $name,
                    'Reporting_VEEVA_ID' => $id,
                    'Reporting_Local_ID' => $view['Local_Employee_ID']
                );
                $this->admin_model->reporting_change($veeva_id[$i], $data);
            }
            redirect('admin/emp_view', 'refresh');
        }
        $data = array('title' => 'Assign', 'content' => 'admin/asign_bdm', 'page_title' => $name, 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    function sendMail2() {
        include APPPATH . 'third_party/phpMailer/class.phpmailer.php';
        include APPPATH . 'third_party/phpMailer/class.smtp.php';

        $email = $this->input->post('email');

        $emp = $this->User_model->employee_id($email);
        if (!empty($emp)) {
            $encodedPassword = base64_encode($emp['VEEVA_Employee_ID']);
            $link = "http://instacom.in/test-bitracking/index.php/User/Reset_Password/?e=" . $encodedPassword;

            $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

            $mail->IsSMTP(); // telling the class to use SMTP

            try {
                $mail->SMTPAuth = true;                  // enable SMTP authentication
                $mail->SMTPSecure = "ssl";                 // sets the prefix to the server
                $mail->Host = "smtpout.asia.secureserver.net";      // sets the SMTP server
                $mail->Port = 465;                   // set the SMTP port for the MAIL server
                $mail->Username = "bi@instacom.in";  //  username
                $mail->Password = "bitracker";            // password

                $mail->FromName = "BI-Tracking";
                $mail->From = "bi@instacom.in";
                $mail->AddAddress($email, "BI-Tracking");

                $mail->Subject = "Forgot Password";

                $mail->IsHTML(true);

                $mail->Body = <<<EMAILBODY

Link For Reseting Password <br/>{$link
                        }
EMAILBODY;

                $mail->Send();
            } catch (phpmailerException $e) {
                echo $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo $e->getMessage(); //Boring error messages from anything else!
            }
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Link For Resetting Password Has Been Mailed To Your Emailid.', 'success'));

            redirect('User/index', 'refresh');
        } else {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Wrong Emailid', 'danger'));
            redirect('User/index', 'refresh');
        }
    }

    function EmployeeDoctor() {
        $this->load->model('User_model');

        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');

        $result = $this->admin_model->find_territory();
        $data['Territory'] = $this->Master_Model->generateDropdown($result, 'ID', 'Territory');

        $result = $this->admin_model->find_Division();
        $data['Division'] = $this->Master_Model->generateDropdown($result, 'Division', 'Division');

        $result = $this->admin_model->find_Profile();
        $data['Profile'] = $this->Master_Model->generateDropdown($result, 'Profile', 'Profile');

        $data['result'] = $this->User_model->getEmployeeDoctor();
        $data = array('title' => 'Employee Doctor', 'content' => 'admin/EmployeeDoctor', 'page_title' => 'Employee Doctor', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function emp_docmaster() {
        $result = $this->admin_model->find_name();
        $data['Name'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name');
        if ($this->input->get('id') != '') {
            $data['Name'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name', $this->input->get('id'));
            $data['show'] = $this->admin_model->namefilter($this->input->get('id'));
        }
        $data['show'] = $this->admin_model->namefilter($this->input->get('id'));
        $fields = array('Full_Name', 'Zone', 'Territory', 'NAME', 'Specialty', 'DM.Account_ID', 'VEEVA_Employee_ID', 'Individual_Type',);
        $array = json_decode(json_encode($data['show']), true, JSON_NUMERIC_CHECK);

        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'Employee Doctor Master', $fields);
        }

        $data = array('title' => 'Employee Doctor', 'content' => 'admin/empdocmaster', 'page_title' => 'Employee Doctor Master', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function assigndoctorlist() {
        $rxarray = array();
        $doctorArray = array();
        if ($this->input->post('newveevaid') != '' && $this->input->post('oldveevaid') != '') {
            $query = $this->db->query("SELECT * FROM Employee_Doc WHERE VEEVA_Employee_ID = '" . $this->input->post('newveevaid') . "'");
            $result = $query->result();
            if (empty($result)) {
                $query = $this->db->query("SELECT * FROM Employee_Doc WHERE VEEVA_Employee_ID = '" . $this->input->post('oldveevaid') . "'");
                $result = $query->result();
                if (!empty($result)) {
                    foreach ($result as $rx) {
                        $rxarray[$rx->VEEVA_Employee_ID . "," . $rx->VEEVA_Account_ID] = $rx->VEEVA_Account_ID;
                    }
                    $oldveevaid = $this->input->post('oldveevaid');
                    $newVEEVAid = $this->input->post('newveevaid');
                    $updateSql = 'UPDATE Employee_Doc ';
                    $updateSql .= "SET VEEVA_Employee_ID = CASE ";
                    if (!empty($rxarray)) {
                        foreach ($rxarray as $key => $value) {
                            $explode = explode(",", $key);
                            $updateSql .= " WHEN VEEVA_Employee_ID = '$explode[0]'  THEN  '$newVEEVAid' ";
                        }
                        $updateSql .= " END ";
                    }
                    $updateSql .= ",CompositeKey = CASE ";

                    if (!empty($rxarray)) {
                        foreach ($rxarray as $key => $value) {
                            $explode = explode(",", $key);
                            $compositeKey = $newVEEVAid . $explode[1];
                            $oldcompositekey = $explode[0] . $explode[1];
                            array_push($doctorArray, "'" . $oldcompositekey . "'");
                            $updateSql .= " WHEN CompositeKey = '$oldcompositekey'  THEN '$compositeKey' ";
                        }
                        $updateSql .= " END ";
                    }
                    $updateSql .=" WHERE VEEVA_Employee_ID = '$oldveevaid'  AND CompositeKey IN";
                    $updateSql .="(" . join(",", $doctorArray) . ")";
                    //echo $updateSql;
                    $this->db->query($updateSql);
                }
                //$this->db->where(array('VEEVA_Employee_ID' => $this->input->post('oldveevaid')));
                //$this->db->update('Employee_Doc', array('VEEVA_Employee_ID' => $this->input->post('newveevaid')));
            } else {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Some Doctors Are Already Assigned ' . $this->input->post('newveevaid'), 'danger'));
            }
        }
        redirect('admin/emp_docmaster', 'refresh');
    }

    function password_list() {
        $data['show'] = $this->admin_model->emp_view();
        $data = array('title' => 'Password List', 'content' => 'admin/PasswordList', 'page_title' => 'Password List', 'view_data' => $data);
        $this->load->view('template3', $data);
    }

    public function PasswordMail() {
        $this->load->model('Encryption');
        $this->load->model('admin_model');
        $sql = "SELECT em.Full_Name,em.Username,em.Password,em.Email_ID FROM `Employee_Master` em INNER JOIN Send_Email s ON s.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` AND s.Status = 1 ";
        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result)) {
            foreach ($result as $value) {

                $message = "<p>Dear $value->Full_Name,<br></p>
                  
<p>Please check the link and login details for BI Tracking  as mentioned below Link :<br>
</p>
<p>
http://instacom.in/test-bitracking/index.php/User <br> </p>
<p>
Username : $value->Username <br></p>
<p>
Password : " . $this->Encryption->decode($value->Password) . "<br></p>
<p>
Kindly contact on the helpline number or mail us for any query regarding the same. <br></p>
<p>
Regards,<br>
BI Support</p>

";

                $this->admin_model->sendMail($value->Email_ID, $message);
                //$this->admin_model->sendMail('akshay@techvertica.com', $message);
            }


            $this->db->query("Update Send_Email SET Status = 2");
        }
    }

}
