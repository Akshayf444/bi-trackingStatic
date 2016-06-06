<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller {

    public $alertLabel = 'Doctor';
    public $doctorIds = array();
    public $Errors = array();

    public function __construct() {
        parent::__construct();
        $this->load->helper();
        $this->load->model('User_model');
        $this->load->model('Master_Model');
        $this->load->model('Doctor_Model');
        $this->load->model('admin_model');
        $this->load->model('Encryption');
        $this->load->library('form_validation');
        $this->Product_List = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $this->calcPlanning();
    }

    public function calcPlanning() {
        $this->db->select('*');
        $this->db->from('Setting');
        $this->db->where('Current_Month', date('n'));
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $value) {
                $this->nextMonth = $value->Planned_For_Month;
                $this->nextYear = $value->Planned_For_Year;
            }
        }

        $this->nextMonth = date('m');
        $this->nextYear = date('Y');
    }

    public function index() {
        $data = array();
        $message = '';
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->Encryption->encode($this->input->post('password'));
            $check = $this->User_model->authentication($username, $password);

            if (empty($check)) {
                $emp = $this->User_model->employee_id($username);
                if (isset($emp['VEEVA_Employee_ID'])) {
                    $count = $this->User_model->password_count($emp['VEEVA_Employee_ID']);
                    if ($count['cnt'] > 4) {
                        $data1 = array(
                            'Status' => 'locked',
                        );
                        $this->User_model->update_status($username, $data1);
                        $data['message'] = 'Your Account Has Been Locked';
                        $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Your Account Has Been Blocked', 'danger'));

                        $message = 'Account Locked';
                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => $message,
                            'VEEVA_Employee_ID' => $emp['VEEVA_Employee_ID'],
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => $emp['Profile'],
                        );
                        $this->User_model->insertLog($logdata);
                    } else {

//Checking what was the last failed attempt for for perticular user .
                        $lastFailed_attempt = $this->User_model->lastFailedAttempt($emp['VEEVA_Employee_ID']);
                        if (!empty($lastFailed_attempt)) {
                            $current_date = date('Y-m-d H:i:s');
                            $current_date = strtotime($current_date);
                            $lastAttemptDate = strtotime($lastFailed_attempt->created_at);

                            if (($current_date - $lastAttemptDate) > 3600) {
                                $data1 = array(
                                    'Status' => '1',
                                );
                                $this->User_model->update_status($username, $data1);
                                $add = array(
                                    'VEEVA_Employee_ID' => $emp['VEEVA_Employee_ID'],
                                    'password' => $password,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'emailid' => $username
                                );
                                $this->User_model->password_save($add);
                                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Username/password Incorrect', 'danger'));
                                $message = 'Incorrect Username/Password';
                            } else {
                                $add = array(
                                    'VEEVA_Employee_ID' => $emp['VEEVA_Employee_ID'],
                                    'password' => $password,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'emailid' => $username
                                );
                                $this->User_model->password_save($add);
                                $message = 'Incorrect Username/Password';
                                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Username/password Incorrect', 'danger'));
                            }
                        } else {
                            $add = array(
                                'VEEVA_Employee_ID' => $emp['VEEVA_Employee_ID'],
                                'password' => $password,
                                'created_at' => date('Y-m-d H:i:s'),
                                'emailid' => $username
                            );
                            $this->User_model->password_save($add);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Username/password Incorrect', 'danger'));
                            $message = 'Incorrect Username/Password';
                        }
                        $message = 'Invalid Username / Password';
                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => $message,
                            'VEEVA_Employee_ID' => $emp['VEEVA_Employee_ID'],
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => $emp['Profile'],
                        );
                        $this->User_model->insertLog($logdata);
                    }
                } else {
                    $add = array(
                        'VEEVA_Employee_ID' => '',
                        'password' => $password,
                        'created_at' => date('Y-m-d H:i:s'),
                        'emailid' => $username
                    );
                    $this->User_model->password_save($add);
                    $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Username/password Incorrect', 'danger'));
                }
            } else {
                $this->session->set_userdata('VEEVA_Employee_ID', $check['VEEVA_Employee_ID']);
                $this->session->set_userdata('Local_Employee_ID', $check['Local_Employee_ID']);
                $this->session->set_userdata('Full_Name', $check['Full_Name']);
                $this->session->set_userdata('Division', $check['Division']);
                $this->session->set_userdata('Designation', $check['Profile']);
                $this->session->set_userdata('Reporting_To', $check['Reporting_To']);
                $this->session->set_userdata('Reporting_VEEVA_ID', $check['Reporting_VEEVA_ID']);
                $this->session->set_userdata('Reporting_Local_ID', $check['Reporting_Local_ID']);
                $this->session->set_userdata('Reporting_To', $check['Reporting_To']);
                $this->session->set_userdata('password_status', $check['password_status']);
                $this->session->set_userdata('Zone', $check['Zone']);
                $this->session->set_userdata('Territory', $check['Territory']);

                $check_password = $this->User_model->password_status($this->session->userdata('VEEVA_Employee_ID'));
                $add = array(
                    'VEEVA_Employee_ID' => $check['VEEVA_Employee_ID'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'password' => $check['password']
                );
                $this->db->insert('login_history', $add);
                $message = 'Login by ' . $check['Full_Name'];
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => $message,
                    'VEEVA_Employee_ID' => $check['VEEVA_Employee_ID'],
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => $check['Profile'],
                );
                $this->User_model->insertLog($logdata);

                if (is_null($check_password['password_status']) || $check_password['password_status'] == '') {
                    redirect('User/password', 'refresh');
                } else {
                    $data = array('Last_Login' => date('Y-m-d H:i:s'));
                    $this->User_model->update_last_login($this->session->userdata('VEEVA_Employee_ID'), $data);
                    if ($check_password['Profile'] === 'ASM') {
                        redirect('ASM/dashboard', 'refresh');
                    } elseif ($check_password['Profile'] === 'ZSM') {
//redirect('Report/dashboard');
                        redirect('Report/dailyTrend?Zone=' . $check_password['Zone'] . '&Division=' . $check_password['Division'], 'refresh');
                    } elseif ($check_password['Profile'] === 'BDM') {
                        redirect('User/dashboard', 'refresh');
                    } elseif ($check_password['Profile'] === 'HO User' || $check_password['Profile'] === 'ETM' || $check_password['Profile'] === 'MD') {
                        redirect('Report/dailyTrend', 'refresh');
                    } elseif ($check_password['Profile'] === 'Marketing' || $check_password['Profile'] === 'NSM') {
                        redirect('Report/dailyTrend?Division=' . $check_password['Division'], 'refresh');
                    } elseif ($check_password['Profile'] === 'ADMIN') {
                        redirect('Admin/emp_view', 'refresh');
                    }
                }
            }
        }
        $data = array('title' => 'Login', 'content' => 'User/login', 'view_data' => $data);
        $this->load->view('template1', $data);
    }

    public function PlanningDr() {
        $data = array('title' => 'PlanningDr', 'content' => 'User/PlanningDr', 'view_data' => 'blank');
        $this->load->view('template2', $data);
    }

    public function dashboard() {

        $this->load->model('admin_model');
        $this->setCutOffDate();
        if ($this->is_logged_in('BDM')) {
            $data = array();
            $result = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['productlist'] = $result;

            $data = array('title' => 'Main', 'content' => 'User/dashboard', 'page_title' => 'Dashboard', 'view_data' => $data);
            $this->load->view('bdmfront', $data);
            $result = $this->Doctor_Model->getDoctor($this->VEEVA_Employee_ID, $this->Individual_Type);
            $this->doctorIds = $this->Doctor_Model->generateDoctorId($result);
        } else {
            $this->logout();
        }
    }

    public function productSel() {
        $data = array('title' => 'Select Product', 'content' => 'User/Product', 'view_data' => 'blank');
        $this->load->view('template2', $data);
    }

    public function Set_Target() {

        if ($this->Product_Id == 1) {
            $alertLabel = "Hospital";
        }
        $target = $this->User_model->Rx_Target_month2($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $this->nextMonth);
        $data['target'] = isset($target['target']) ? $target['target'] : 0;

        $month_start = date('n', strtotime('-4 month'));
        $month_mid = date('n', strtotime('-3 month'));
        $month_between = date('n', strtotime('-2 month'));
        $month_ends = date('n', strtotime('-1 month'));
        $current_month = date('n');
        $year = date('Y');

        $data['Actual1'] = $this->User_model->Actual_Rx_Target_month($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $month_start, $year);
        $data['Actual2'] = $this->User_model->Actual_Rx_Target_month($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $month_mid, $year);
        $data['Actual3'] = $this->User_model->Actual_Rx_Target_month($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $month_between, $year);
        $data['Actual4'] = $this->User_model->Actual_Rx_Target_month($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $month_ends, $year);
        $data['date'] = date('M', strtotime('+1 month'));
        $data['month_mid'] = date('M', strtotime('-4 month'));
        $data['month_start'] = date('M', strtotime('-3 month'));
        $data['month_between'] = date('M', strtotime('-2 month'));
        $data['month_ends'] = date('M', strtotime('-1 month'));
        $data['current_month'] = date('M');
        $data['Product_Id'] = $this->Product_Id;

        $data = array('title' => 'Report', 'content' => 'User/addDelta', 'backUrl' => 'User/dashboard', 'view_data' => $data);
        $this->load->view('template2', $data);
    }

    public function Profiling() {
        $Doctor_Id = '0';
        
        $this->setProductId();
        
        if ($this->input->get('Doctor_Id')) {
            $Doctor_Id = $this->input->get('Doctor_Id');
        }

        $messages = array();
        if ($this->is_logged_in('BDM')) {
            $result = $this->Doctor_Model->getProfiledDoctor($this->VEEVA_Employee_ID, $this->Product_Id, $this->Individual_Type, $this->Cycle);
//var_dump($result);
            if ($this->input->post()) {
                $_POST['VEEVA_Employee_ID'] = $this->VEEVA_Employee_ID;
                $_POST['Product_id'] = $this->Product_Id;
                $_POST['Status'] = $this->input->post('Status');
                $_POST['Cycle'] = $this->Cycle;

                $check = $this->User_model->profiling_by_id($_POST['Doctor_id'], $_POST['VEEVA_Employee_ID'], $_POST['Product_id'], $_POST['Cycle']);
                if (isset($_POST['Win_Q1']) && $_POST['Win_Q1'] != '' && isset($_POST['Win_Q2']) && $_POST['Win_Q2'] != '' && isset($_POST['Win_Q3']) && $_POST['Win_Q3'] != '') {
                    $_POST['Winability'] = $this->User_model->calcWinability($_POST['Win_Q1'], $_POST['Win_Q2'], $_POST['Win_Q3']);

                    if (empty($check)) {
                        $_POST['created_at'] = date('Y-m-d H:i:s');
                        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                            $_POST['Product_id'] = 4;
                            $this->db->insert('Profiling', $_POST);
                            $_POST['Product_id'] = 6;
                            $this->db->insert('Profiling', $_POST);
                            $_POST['Product_id'] = 5;
                            $_POST['Win_Q1'] = '';
                            $_POST['Win_Q2'] = '';
                            $_POST['Win_Q3'] = '';
                            $_POST['Patient_Rxbed_In_Week'] = '';
                            $_POST['Patient_Rxbed_In_Month'] = '';
                            $_POST['Winability'] = '';
                            $_POST['Status'] = 'Draft';
                            $this->db->insert('Profiling', $_POST);
                            $message = "Profile Added For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Added Successfully.', 'success'));
                            $this->redirects('Profiling', $this->Product_Id);
                        } elseif ($this->Product_Id == 5) {
                            $_POST['Product_id'] = 5;
                            $this->db->insert('Profiling', $_POST);

                            $_POST['Product_id'] = 6;
                            $_POST['Win_Q1'] = '';
                            $_POST['Win_Q2'] = '';
                            $_POST['Win_Q3'] = '';
                            $_POST['Patient_Rxbed_In_Week'] = '';
                            $_POST['Patient_Rxbed_In_Month'] = '';
                            $_POST['Winability'] = '';
                            $_POST['Status'] = 'Draft';
                            $this->db->insert('Profiling', $_POST);
                            $_POST['Product_id'] = 4;
                            $this->db->insert('Profiling', $_POST);
                            $message = "Profile Added For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Added Successfully.', 'success'));
                            $this->redirects('Profiling', $this->Product_Id);
                        } else {
                            $this->db->insert('Profiling', $_POST);
                            $message = "Profile Added For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Added Successfully.', 'success'));
                            $this->redirects('Profiling', $this->Product_Id);
                        }
                    } elseif ($check['Status'] == 'Draft') {
                        $_POST['updated_at'] = date('Y-m-d H:i:s');
                        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                            $_POST['Product_id'] = 4;
                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 4, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $_POST);

                            $_POST['Product_id'] = 6;
                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 6, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $_POST);

                            $field_array = array(
                                'Patient_Seen' => $_POST['Patient_Seen'],
                                'Patient_Seen_month' => $_POST['Patient_Seen_month'],
                            );

                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 5, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $field_array);
                            $message = "Profile Updated For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Updated Successfully.', 'success'));
                            $this->redirects('Profiling', $this->Product_Id);
                        } elseif ($this->Product_Id == 5) {
                            $_POST['Product_id'] = 5;
                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 5, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $_POST);

                            $field_array = array(
                                'Patient_Seen' => $_POST['Patient_Seen'],
                                'Patient_Seen_month' => $_POST['Patient_Seen_month'],
                            );

                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 6, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $field_array);

                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => 4, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $field_array);
                            $message = "Profile Updated For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Updated Successfully.', 'success'));
                            $this->redirects('Profiling', $this->Product_Id);
                        } else {
                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => $this->Product_Id, 'Doctor_id' => $_POST['Doctor_id'], 'Cycle' => $this->Cycle));
                            $this->db->update('Profiling', $_POST);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Updated Successfully.', 'success'));

                            $message = "Profile Updated For " . $_POST['Doctor_id'];
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => $message,
                                'VEEVA_Employee_ID' => $_POST['VEEVA_Employee_ID'],
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
//$this->User_model->insertLog($logdata);
                            $this->redirects('Profiling', $this->Product_Id);
                        }
                    } elseif ($check['Status'] == 'Submitted') {
                        $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($this->alertLabel . ' Profile Already Submitted .', 'danger'));
                        redirect('User/Doctorlist2', 'refresh');
                    }
                } else {
                    $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Please Answer Winability Questions .', 'danger'));
                }
            }

            $data['Product_Id'] = $this->Product_Id;
            $data['doctorList'] = $this->Master_Model->generateProfileDropdown($result, $Doctor_Id);
            $data['questionList'] = $this->Master_Model->getQuestions($this->Product_Id);
            $data = array('title' => 'Question', 'content' => 'User/Question', 'backUrl' => 'User/dashboard', 'page_title' => 'Profiling', 'view_data' => $data);
            $this->load->view('bdmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function PlanningMenu() {
        $data['productlist'] = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $data['site_url'] = 'User/Planning';
        $data = array('title' => 'Planning', 'content' => 'User/PlanningMenu', 'page_title' => 'Choose Product', 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function Planning() {
        $this->setProductId();
        $created_at = $this->setCutOffDate();

        $messages = array();
        $logmessage = array();
        if ($this->is_logged_in('BDM')) {
            $targetSet = $this->User_model->Rx_Target_month($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth, $this->nextYear);
            if (!empty($targetSet) && $targetSet->target > 0) {
                $data['result'] = $this->User_model->getPlanning($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth, $this->nextYear);
// echo($data['doctorList']);
                if ($this->input->post()) {
                    $currentPlanned = array_sum($this->input->post('value'));
                    $currentPlanned = (int) $currentPlanned;
                    $value = $this->input->post('value');
                    $doc_id = $this->input->post('doc_id');
//var_dump($doc_id);
                    for ($i = 0; $i < count($this->input->post('value')); $i++) {

                        $result = $this->User_model->PlanningExist($doc_id[$i]);
//var_dump($result);
                        $current_date = date('Y-m-d', strtotime($created_at));
                        $next_date = date('M');
                        $doc = array(
                            'Planned_Rx' => $value[$i],
                            'Year' => $this->nextYear,
                            'month' => $this->nextMonth,
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'Product_Id' => $this->Product_Id,
                            'Doctor_Id' => $doc_id[$i],
                            'Planning_Status' => $this->input->post('Planning_Status')
                        );
                        if (empty($result)) {
                            $doc['created_at'] = $created_at;
                            $doc['Approve_Status'] = 'Draft';
                            if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                $doc['Approve_Status'] = 'SFA';
                            }
                            if ($this->User_model->Save_Planning($doc)) {
                                array_push($messages, $this->Master_Model->DisplayAlert('The Planning for ' . date('M') . '' . $this->nextYear . ' has been saved successfully! Thank you!.', 'success'));
                                array_push($logmessage, 'The Planning for ' . date('M', strtotime($created_at)) . '' . $this->nextYear . ' has been Added.');
                            }
                        } elseif (isset($result->Planning_Status) && $result->Planning_Status == 'Draft') {

                            if ($result->Planned_Rx != $value[$i]) {
                                $doc['field_changed'] = 1;
                            }

                            if ($result->Planned_Rx != $value[$i] || $result->Approve_Status == 'Draft' || $result->field_changed == 1) {
                                if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                    $doc['Approve_Status'] = 'SFA';
                                }
                            } else {
                                $doc['Approve_Status'] = $result->Approve_Status;
                            }
                            $doc['updated_at'] = $created_at;
                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'Doctor_Id' => $doc_id[$i], 'month' => $this->nextMonth));
                            //$this->db->update('Rx_Planning', $doc);
                            array_push($messages, $this->Master_Model->DisplayAlert('The Planning for ' . date('M', strtotime($created_at)) . '' . $this->nextYear . ' has been Updated successfully! Thank you!.', 'success'));
                            array_push($logmessage, 'The Planning for ' . date('M', strtotime($created_at)) . '' . $this->nextYear . ' has been Updated.');
                        } elseif (isset($result->Planning_Status) && $result->Planning_Status == 'Submitted') {
                            array_push($logmessage, 'The Planning for ' . date('M', strtotime($created_at)) . '' . $this->nextYear . ' Already Submitted.');
                            array_push($messages, $this->Master_Model->DisplayAlert('The Planning for ' . date('M', strtotime($created_at)) . '' . $this->nextYear . ' Already Submitted ! Thank you!.', 'danger'));
                        }
                    }
                    if (!empty($messages)) {
                        $this->session->set_userdata('message', join(" ", array_unique($messages)));

                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => join(" ", array_unique($logmessage)),
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => 'BDM',
                        );
                        $this->User_model->insertLog($logdata);
                    }

                    $this->redirects('Planning', $this->Product_Id);
                }
            } else {
                $message = $this->Master_Model->DisplayAlert('Target Is Not Assigned.', 'danger');
                $this->session->set_userdata('message', $message);
            }

            $current_month = $this->nextMonth;
            $data['show4'] = $this->User_model->Rx_Target_month2($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $current_month);
            $data['expected'] = $this->User_model->Expected_Rx($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth);
            $data = array('title' => 'Planning', 'content' => 'User/doctorList', 'page_title' => 'Planning For ' . $this->User_model->getMonthName($this->nextMonth) . ' ' . $this->nextYear, 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
            $this->load->view('bdmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function ReportingMenu() {
        $data['productlist'] = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $data['site_url'] = 'User/Reporting';
        $data = array('title' => 'Planning', 'content' => 'User/PlanningMenu', 'page_title' => 'Choose Product', 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function ActivityMenu() {
        $data['productlist'] = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $data['site_url'] = 'User/ActivityPlanning';
        $data = array('title' => 'Planning', 'content' => 'User/PlanningMenu', 'page_title' => 'Choose Product', 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function ActivityReportingMenu() {
        $data['productlist'] = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $data['site_url'] = 'User/ActivityReporting';
        $data = array('title' => 'Planning', 'content' => 'User/PlanningMenu', 'page_title' => 'Choose Product', 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function ActivityPlanning() {
        $this->setProductId();
        $created_at = $this->setCutOffDate();
        $check_planning = $this->User_model->priority_check($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth);
        if (!empty($check_planning)) {
            if ($this->Product_Id == 1) {
                $this->alertLabel = "Hospital";
            }
            $messages = array();
            $logmessage = array();
            $result = $this->User_model->getActivityDoctor();
            $data['doctorList'] = $this->User_model->generateActivityTable($result);

            if ($this->input->post()) {
                for ($i = 0; $i < count($this->input->post('Doctor_Id')); $i ++) {
                    $docid = $this->input->post('Doctor_Id');
                    $Activity = $this->input->post('Activity_Id');
                    if (trim($Activity[$i]) != '') {
                        $data2 = array(
                            'Activity_Id' => $Activity[$i],
                            'Doctor_Id' => $docid[$i],
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'Product_Id' => $this->Product_Id,
                            'Status' => $this->input->post('Status'),
                            'Year' => $this->nextYear,
                            'month' => $this->nextMonth
                        );

                        $result = $this->User_model->ActivityPlanned($docid[$i]);
                        if (empty($result)) {
                            $data2['created_at'] = $created_at;
                            $data2['Approve_Status'] = 'Draft';
                            if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                $data2['Approve_Status'] = 'SFA';
                            }
                            if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                                $data2['Product_Id'] = 4;
                                $this->db->insert('Activity_Planning', $data2);
                                $data2['Product_Id'] = 6;
                                $this->db->insert('Activity_Planning', $data2);
                                array_push($logmessage, 'Activity Planning Added');
                                array_push($messages, $this->Master_Model->DisplayAlert('Activity Planned Successfully.', 'success'));
                            } else {
                                $this->db->insert('Activity_Planning', $data2);
                                array_push($logmessage, 'Activity Planning Added');
                                array_push($messages, $this->Master_Model->DisplayAlert('Activity Planned Successfully.', 'success'));
                            }
                        } elseif (isset($result->Status) && $result->Status == 'Draft') {
                            $data2['updated_at'] = $created_at;
                            if ($result->Activity_Id != $Activity[$i]) {
                                $data2['field_changed'] = 1;
                            }

                            if ($result->Activity_Id != $Activity[$i] || $result->Approve_Status == 'Draft' || $result->field_changed == 1) {
                                if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                    $data2['Approve_Status'] = 'SFA';
                                }
                            } else {
                                $data2['Approve_Status'] = $result->Approve_Status;
                            }

                            if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                                $data2['Product_Id'] = 4;
                                $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => 4, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $this->nextMonth));
                                $this->db->update('Activity_Planning', $data2);

                                $data2['Product_Id'] = 6;
                                $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => 6, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $this->nextMonth));
                                $this->db->update('Activity_Planning', $data2);
                                array_push($logmessage, 'Activity Planning Updated');
                                array_push($messages, $this->Master_Model->DisplayAlert('Activity Updated Successfully.', 'success'));
                            } else {
                                $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $this->nextMonth));
                                $this->db->update('Activity_Planning', $data2);
                                array_push($logmessage, 'Activity Planning Updated');
                                array_push($messages, $this->Master_Model->DisplayAlert('Activity Updated Successfully.', 'success'));
                            }
                        } elseif (isset($result->Status) && $result->Status == 'Submitted') {
                            array_push($logmessage, 'Activity Planning Already Submitted');
                            array_push($messages, $this->Master_Model->DisplayAlert('Data Already Submitted.', 'danger'));
                        }
                    }
                }
                if (!empty($messages)) {
                    $this->session->set_userdata('message', join(" ", array_unique($messages)));
                    $logdata = array(
                        'date' => date('Y-m-d H:i:s'),
                        'description' => join(" ", array_unique($logmessage)),
                        'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                        'ip_address' => $this->input->ip_address(),
                        'Profile' => 'BDM',
                    );
                    $this->User_model->insertLog($logdata);
                }
                $this->redirects('ActivityPlanning', $this->Product_Id);
            }
        } else {
            $data['doctorList'] = "<h1>" . $this->alertLabel . " Are Not Prioritized</h1>";
        }
        $data = array('title' => 'Activity Planning', 'content' => 'User/Act_Plan', 'page_title' => 'Activity Planning For ' . $this->User_model->getMonthName($this->nextMonth) . ' ' . $this->nextYear, 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function PlanMenu() {
        if ($this->is_logged_in('BDM')) {
            $data['activity_planned'] = $this->User_model->activity_planned($this->VEEVA_Employee_ID, $this->Product_Id);
            $data['prio_dr'] = $this->User_model->prio_dr($this->VEEVA_Employee_ID, $this->Product_Id);
            $data['asm_comment'] = $this->User_model->ASM_comment($this->VEEVA_Employee_ID, $this->Product_Id);
            $data = array('title' => 'Report', 'content' => 'User/PlanMenu', 'backUrl' => 'User/dashboard', 'view_data' => $data);
            $this->load->view('template2', $data);
        } else {
            $this->logout();
        }
    }

    public function password() {
//if ($this->is_logged_in('BDM')) {
        if ($this->input->post()) {
            $password = $this->input->post('password');
            if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $password)) {
                if ($this->input->post('password') === $this->input->post('password2')) {
                    if (!empty($password)) {
                        $password = $this->Encryption->encode($password);
                        $data = array(
                            'password' => $password,
                            'password_status' => 'Active',
                        );
                        $this->User_model->password($this->session->userdata('VEEVA_Employee_ID'), $data);

                        $message = 'Password Change After Login';
                        $check_password = $this->User_model->password_status($this->session->userdata('VEEVA_Employee_ID'));
                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => $message,
                            'VEEVA_Employee_ID' => $check_password['VEEVA_Employee_ID'],
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => $check_password['Profile'],
                        );
                        $this->session->set_userdata('password_status', 'Active');
                        $this->password_status = 'Active';

//var_dump($check_password);
                        $this->User_model->insertLog($logdata);
                        if ($check_password['Profile'] == 'ASM') {
                            redirect('ASM/dashboard', 'refresh');
                        } elseif ($check_password['Profile'] == 'BDM') {
                            redirect('User/dashboard', 'refresh');
                        } elseif ($check_password['Profile'] == 'ZSM') {
//redirect('ZSM/dashboard');
                            redirect('Report/dailyTrend?Zone=' . $check_password['Zone'] . '&Division=' . $check_password['Division'], 'refresh');
                        } elseif ($check_password['Profile'] === 'HO User' || $check_password['Profile'] === 'ETM' || $check_password['Profile'] === 'MD') {
                            redirect('Report/dailyTrend', 'refresh');
                        } elseif ($check_password['Profile'] === 'Marketing' || $check_password['Profile'] === 'NSM') {
                            redirect('Report/dailyTrend?Division=' . $check_password['Division'], 'refresh');
                        } elseif ($check_password['Profile'] === 'ADMIN') {
                            redirect('Admin/emp_view', 'refresh');
                        }
                    }
                } else {
                    $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Password And Its Repeat Must Be Same', 'danger'));
                }
            } else {
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Password Must Contain 8 characters with 1 Uppercase Alphabet, 1 Lowercase Alphabet and 1 Number', 'danger'));
            }
        }
        $data = array('title' => 'Change Password', 'content' => 'User/password', 'view_data' => 'blank');
        $this->load->view('template2', $data);
    }

    public function Reporting() {
        $this->setProductId();
        $messages = array();
        $logmessage = array();

        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];
        $this->nextYear = date('Y', strtotime($created_at));

        $data['current_month'] = $current_month;
        if ($this->is_logged_in('BDM')) {
            $data['show4'] = $this->User_model->Rx_Target_month2($this->VEEVA_Employee_ID, $this->Product_Id, $current_month);
            $check_planning = $this->User_model->check_planning($this->VEEVA_Employee_ID, $this->Product_Id, $current_month, $this->nextYear);
            //var_dump($check_planning);
            if (!empty($check_planning)) {
                $message = $this->Master_Model->DisplayAlert('Rx / Vials Reporting For ' . date('M', strtotime($created_at)), 'danger');
                $this->session->set_userdata('message', $message);
                $data['result'] = $this->User_model->getReporting($this->VEEVA_Employee_ID, $this->Product_Id, $current_month, $this->nextYear, $created_at);

                if ($this->input->post()) {
                    for ($i = 0; $i < count($this->input->post('doc_id')); $i++) {
                        $value = $this->input->post('value');
                        $doc_id = $this->input->post('doc_id');
                        $current_date = date('Y-m-d');
                        $next_date = date('M');
                        $doc = array(
                            'Actual_Rx' => $value[$i],
                            'Year' => $this->nextYear,
                            'month' => $current_month,
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'Product_Id' => $this->Product_Id,
                            'Doctor_Id' => $doc_id[$i],
                            'Status' => $this->input->post('Status'),
                        );

                        $result = $this->User_model->ReportingExist($doc_id[$i], $current_month, $created_at);

                        if (empty($result)) {
                            $doc['created_at'] = $created_at;
                            $doc['Approve_Status'] = 'Draft';
                            if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                $doc['Approve_Status'] = 'SFA';
                            }
                            if ($this->User_model->SaveReporting($doc)) {
                                array_push($messages, $this->Master_Model->DisplayAlert('Reporting Data Added Successfully.', 'success'));
                                array_push($logmessage, 'Reporting Data Added.');
                            }
                        } else {
                            if (isset($result->Status) && $result->Status == 'Draft') {
                                if ($result->Actual_Rx != $value[$i]) {
                                    $doc['field_changed'] = 1;
                                }
                                if ($result->Actual_Rx != $value[$i] || $result->Approve_Status == 'Draft' || $result->field_changed == 1) {
                                    if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                        $doc['Approve_Status'] = 'SFA';
                                    }
                                } else {
                                    $doc['Approve_Status'] = $result->Approve_Status;
                                }
                                $doc['updated_at'] = $created_at;
                                $this->db->where(array('Rxplan_id' => (int) $result->Rxplan_id));
                                if ($this->db->update('Rx_Actual', $doc)) {
                                    array_push($logmessage, 'Reporting Data Updated.');
                                    array_push($messages, $this->Master_Model->DisplayAlert('Reporting Data Updated Successfully.', 'success'));
                                }
                            } else {
                                if (isset($result->Status) && $result->Status == 'Submitted') {
                                    array_push($logmessage, 'Reporting Data Already Submitted.');
                                    array_push($messages, $this->Master_Model->DisplayAlert('Reporting Data Already Submitted For ' . date('M', strtotime($created_at)) . '' . $this->nextYear, 'danger'));
                                }
                            }
                        }
                    }
                    if (!empty($messages)) {
                        $this->session->set_userdata('message', join(" ", array_unique($messages)));
                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => join(" ", array_unique($logmessage)),
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => 'BDM',
                        );
                        $this->User_model->insertLog($logdata);
                    }
                    $this->redirects('Reporting', $this->Product_Id);
                }

//echo $data['doctorList'] ;
            } else {
                $data['doctorList'] = "<h1>Please Save Planning First</h1>";
            }
            $data = array('title' => 'Reporting Doctor', 'content' => 'User/Prescription_Doctor_List', 'page_title' => 'Reporting For ' . $this->User_model->getMonthName($current_month) . ' ' . $this->nextYear, 'backUrl' => 'User/dashboard', 'view_data' => $data);
            $this->load->view('bdmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function PriorityMenu() {
        $data['productlist'] = $this->Master_Model->BrandList($this->session->userdata('Division'));
        $data['site_url'] = 'User/Priority';
        $data = array('title' => 'Planning', 'content' => 'User/PlanningMenu', 'page_title' => 'Choose Product', 'backUrl' => 'User/PlanMenu', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function Priority() {
        $data = array();
        $this->setProductId();
        $messages = array();
        $logmessage = array();
        $doctor_ids = $this->User_model->PriorityIds();
//var_dump($doctor_ids);
        if (!empty($doctor_ids)) {
            $data['doctorList'] = $this->User_model->generatePlanningTab('Planning', 'true', $doctor_ids);
        }

        if ($this->input->post()) {
            for ($i = 0; $i < count($this->input->post('priority')); $i++) {

                $priority = $this->input->post('priority');
                $result = $this->User_model->ActualPriorityExist($priority[$i]);
                $data2 = array(
                    'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                    'Product_Id' => $this->Product_Id,
                    'month' => $this->nextMonth,
                    'Doctor_Id' => $priority[$i],
                    'Status' => 'Submitted',
                    'created_at' => date('Y-m-d H:i:s'),
                    'Year' => $this->nextYear
                );
                if (empty($result)) {
                    $this->db->insert('Actual_Doctor_Priority', $data2);
                    array_push($logmessage, $this->alertLabel . ' Priority Added .');
                    array_push($messages, $this->Master_Model->DisplayAlert($this->alertLabel . ' Priority Added .', 'success'));
                } elseif ($result['Status'] == 'Draft') {
                    $this->db->where(array('VEEVA_Employee_Id' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'month' => $this->nextMonth, 'Doctor_Id' => $priority[$i]));
                    $this->db->update('Actual_Doctor_Priority', $data2);
                    array_push($logmessage, $this->alertLabel . ' Priority Updated .');
                    array_push($messages, $this->Master_Model->DisplayAlert($this->alertLabel . ' Priority Updated .', 'success'));
                } elseif ($result['Status'] == 'Submitted') {
                    //array_push($logmessage, $this->alertLabel . ' Priority Already Submitted .');
                    //array_push($messages, $this->Master_Model->DisplayAlert($this->alertLabel . ' Priority Already Submitted .', 'danger'));
                }
            }
            if (!empty($messages)) {
                $this->session->set_userdata('message', join(" ", array_unique($messages)));
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => join(" ", array_unique($logmessage)),
                    'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => 'BDM',
                );
                $this->User_model->insertLog($logdata);
            }
            redirect('User/dashboard', 'refresh');
        }

        $data = array('title' => 'Set Priority', 'content' => 'User/Priority', 'page_title' => 'Set Priority', 'backUrl' => 'User/dashboard', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    public function ActivityReporting() {
        $this->setProductId();

        if ($this->is_logged_in('BDM')) {
            $cutoffdates = $this->User_model->CutOfDate();
            $current_month = $cutoffdates[0];
            $created_at = $cutoffdates[1];
            $current_year = date('Y', strtotime($created_at));

            $data['current_month'] = $current_month;
            $Status = "Submitted";
            $check = $this->User_model->Activity_reporting_check($this->VEEVA_Employee_ID, $this->Product_Id, $Status, $current_month, $current_year);

            if (!empty($check)) {
                $message = $this->Master_Model->DisplayAlert('Activity Reporting For ' . date('M', strtotime($created_at)), 'danger');
                $this->session->set_userdata('message', $message);
                if ($this->Product_Id == 1) {
                    $this->alertLabel = "Hospital";
                }

                $messages = array();
                $logmessage = array();
                $result = $this->User_model->getPlannedActivityDoctor($current_month);
                //var_dump($result);
                $data['doctorList'] = $this->User_model->generateActivityTable($result, 'Reporting');
                $Activity_Detail = $this->input->post('Activity_Detail');
                $Reason = $this->input->post('Reason');

                if ($this->input->post()) {
                    //var_dump($_POST);
                    $Activity_Detail = $this->input->post('Activity_Detail');
                    $Reason = $this->input->post('Reason');
                    for ($i = 0; $i < count($this->input->post('Doctor_Id')); $i ++) {
                        $docid = $this->input->post('Doctor_Id');
                        $Activity = $this->input->post('Activity_Id');
                        $reason = trim($this->input->post($docid[$i] . 'Reason'));
                        $activitydetail = trim($this->input->post($docid[$i] . 'Activity_Detail'));
                        if (trim($Activity[$i]) != '-1') {
                            if ($this->input->post($docid[$i]) == 'Yes' || $this->input->post($docid[$i]) == 'No') {
                                if ($activitydetail != '' || $reason != '') {
                                    $data2 = array(
                                        'Activity_Id' => $Activity[$i],
                                        'Doctor_Id' => $docid[$i],
                                        'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                                        'Product_Id' => $this->Product_Id,
                                        'Status' => $this->input->post('Status'),
                                        'Year' => $this->nextYear,
                                        'month' => $current_month,
                                        'Activity_Done' => $this->input->post($docid[$i])
                                    );
                                    if ($this->input->post($docid[$i]) == 'Yes') {
                                        $data2['Activity_Detail'] = $this->input->post($docid[$i] . 'Activity_Detail');
                                        $data2['Reason'] = '';
                                    } elseif ($this->input->post($docid[$i]) == 'No') {
                                        $data2['Reason'] = $this->input->post($docid[$i] . 'Reason');
                                        $data2['Activity_Detail'] = '';
                                    }

                                    $result = $this->User_model->ActivityReportingExist($docid[$i], $current_month);
                                    if (empty($result)) {
                                        $data2['created_at'] = $created_at;
                                        $data2['Approve_Status'] = 'Draft';
                                        if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                            $data2['Approve_Status'] = 'SFA';
                                        }

                                        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                                            $data2['Product_Id'] = 4;
                                            $this->db->insert('Activity_Reporting', $data2);
                                            $data2['Product_Id'] = 6;
                                            $this->db->insert('Activity_Reporting', $data2);
                                            array_push($messages, $this->Master_Model->DisplayAlert('Activity Added Successfully.', 'success'));
                                            array_push($logmessage, 'Activity Reporting Added.');
                                        } else {
                                            $this->db->insert('Activity_Reporting', $data2);
                                            array_push($logmessage, 'Activity Reporting Added.');
                                            array_push($messages, $this->Master_Model->DisplayAlert('Activity Added Successfully.', 'success'));
                                        }
                                    } elseif (isset($result->Status) && $result->Status == 'Draft') {

                                        if ($this->input->post($docid[$i]) != $result->Activity_Done) {
                                            $data2['field_changed'] = 1;
                                        }

                                        $data2['updated_at'] = $created_at;
                                        if ($this->input->post($docid[$i]) != $result->Activity_Done || $result->Approve_Status == 'Draft' || $result->field_changed == 1) {
                                            if ($this->input->post('Button_click_status') == 'SaveForApproval') {
                                                $data2['Approve_Status'] = 'SFA';
                                            }
                                        } else {
                                            $data2['Approve_Status'] = $result->Approve_Status;
                                        }

                                        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
                                            $data2['Product_Id'] = 4;
                                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => 4, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $current_month));
                                            $this->db->update('Activity_Reporting', $data2);
                                            $data2['Product_Id'] = 6;
                                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => 6, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $current_month));
                                            $this->db->update('Activity_Reporting', $data2);
                                            array_push($logmessage, 'Activity Reporting Updated.');
                                            array_push($messages, $this->Master_Model->DisplayAlert('Activities Updated Successfully.', 'success'));
                                        } else {
                                            $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'Doctor_Id' => $docid[$i], 'Year' => $this->nextYear, 'month' => $current_month));
                                            $this->db->update('Activity_Reporting', $data2);
                                            array_push($logmessage, 'Activity Reporting Updated.');
                                            array_push($messages, $this->Master_Model->DisplayAlert('Activities Updated Successfully.', 'success'));
                                        }
                                    } elseif (isset($result->Status) && $result->Status == 'Submitted') {
                                        array_push($messages, $this->Master_Model->DisplayAlert('Activity Reporting Already Submitted ', 'danger'));
                                    }
                                } else {
                                    array_push($messages, $this->Master_Model->DisplayAlert('Please Fill The Activity Details', 'danger'));
                                }
                            } else {
                                array_push($messages, $this->Master_Model->DisplayAlert('Please Select Yes Or No For Activity Done ', 'danger'));
                            }
                        }
                    }

                    if (!empty($messages)) {
                        $this->session->set_userdata('message', join(" ", array_unique($messages)));
                        $logdata = array(
                            'date' => date('Y-m-d H:i:s'),
                            'description' => join(" ", array_unique($logmessage)),
                            'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                            'ip_address' => $this->input->ip_address(),
                            'Profile' => 'BDM',
                        );
                        $this->User_model->insertLog($logdata);
                    }
                    $this->redirects('ActivityReporting', $this->Product_Id);
                }
            } else {
                $data['doctorList'] = "Activity Planning Not Submitted";
            }
            $data['asm_comment'] = $this->User_model->ASM_comment_rep($this->VEEVA_Employee_ID, $this->Product_Id);
            $data = array('title' => 'Activity Planning', 'content' => 'User/Act_Report', 'backUrl' => 'User/dashboard', 'page_title' => 'Activity Reporting For ' . $this->User_model->getMonthname($current_month) . ' ' . $this->nextYear, 'view_data' => $data);
            $this->load->view('bdmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function getProfilingData() {
        $Doctor_Id = $this->input->post('Doctor_Id');
        $Product_Id = $this->input->post('Product_Id');
        $ProfilingDetails = $this->User_model->profiling_by_id($Doctor_Id, $this->VEEVA_Employee_ID, $Product_Id, $this->Cycle);

        if (!empty($ProfilingDetails)) {
            echo json_encode($ProfilingDetails);
        } else {
            echo '404';
        }
    }

    public function generatePriority() {
        $this->setProductId();
        if ($this->input->post()) {
// if (empty($result)) {
            $currentPlanned = array_sum($this->input->post('value'));
            $currentPlanned = (int) $currentPlanned;
            for ($i = 0; $i < count($this->input->post('value')); $i++) {

                $value = $this->input->post('value');
                $doc_id = $this->input->post('doc_id');

                $result = $this->User_model->PriorityExist($doc_id[$i]);
                $month = date('n', strtotime('-1 month'));
                $month3 = $this->User_model->getMonthwiseRx($doc_id[$i], $month);
                $month3rx = isset($month3->Actual_Rx) ? $month3->Actual_Rx : 0;
                if ($currentPlanned > 0) {
                    $currentDependancy = round(($value[$i] / $currentPlanned) * 100, 0, PHP_ROUND_HALF_EVEN);
                } else {
                    $currentDependancy = 0;
                }

                $data2 = array('Delta' => $value[$i] - $month3rx, 'Dependancy' => $currentDependancy, 'Doctor_Id' => $doc_id[$i], 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'month' => $this->nextMonth, 'Product_Id' => $this->Product_Id, 'Planned_Rx' => $value[$i]);

                if (empty($result)) {
                    $this->db->insert('Doctor_Priority', $data2);
                    $this->message = $this->Master_Model->DisplayAlert('Doctor Priority ' . date('M', strtotime($this->nextMonth)) . '' . $this->nextYear . ' has been saved successfully! Thank you!.', 'success');
                } else {
                    $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_id' => $this->Product_Id, 'Doctor_id' => $doc_id[$i]));
                    $this->db->update('Doctor_Priority', $data2);
                    $this->message = $this->Master_Model->DisplayAlert('Doctor Priority ' . date('M', strtotime($this->nextMonth)) . '' . $this->nextYear . ' has been Updated successfully! Thank you!.', 'success');
                }
            }
            redirect('User/priority?Product_Id=' . $this->Product_Id, 'refresh');
        }
    }

    public function BDM_update() {
        if ($this->is_logged_in('BDM')) {
            if ($this->input->post()) {
                $number = $this->input->post('mobile');
                $date = $this->input->post('date');
                $date1 = date('m-d-y', strtotime($date));
                $mobile = array('Mobile' => $number, 'DOB' => $date1);
                $mob = $this->User_model->Update_mobile($this->VEEVA_Employee_ID, $mobile);
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Update Successfully.', 'success'));
            }
            $data['detail'] = $this->User_model->All_data($this->VEEVA_Employee_ID);
            $data = array('title' => 'Profile Update', 'content' => 'User/Profile_Update', 'view_data' => $data, 'backUrl' => 'User/dashboard');
            $this->load->view('template2', $data);
        } else {
            $this->logout();
        }
    }

    public function pwd_update() {
        if ($this->is_logged_in('BDM')) {
            if ($this->input->post()) {

                $old = $this->Encryption->encode($this->input->post('old'));
                $new = $this->Encryption->encode($this->input->post('new'));
                $pass_exit_history = $this->User_model->check_history($this->VEEVA_Employee_ID, $new);
                $pass = $this->User_model->All_data($this->VEEVA_Employee_ID);
                if (empty($pass_exit_history)) {
                    if (!empty($pass)) {
                        if ($old == $pass['password']) {
                            $mobile = array('password' => $new);
                            $mob = $this->User_model->Update_mobile($this->VEEVA_Employee_ID, $mobile);
                            $data = array('password' => $new,
                                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                                'created_at' => date('y-m-d'));
                            $this->User_model->insert_pass($data);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Password Changed Successfully.', 'success'));
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => 'Password Changed',
                                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
                            $this->User_model->insertLog($logdata);
                        } else {
                            $logdata = array(
                                'date' => date('Y-m-d H:i:s'),
                                'description' => 'Old Password Does Not Match',
                                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                                'ip_address' => $this->input->ip_address(),
                                'Profile' => 'BDM',
                            );
                            $this->User_model->insertLog($logdata);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Old Password Not Match.', 'danger'));
                        }
                    }
                } else {
                    $logdata = array(
                        'date' => date('Y-m-d H:i:s'),
                        'description' => 'Cannot Use Old Password Again',
                        'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                        'ip_address' => $this->input->ip_address(),
                        'Profile' => 'BDM',
                    );
                    $this->User_model->insertLog($logdata);
                    $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(' Already Exit Password .', 'danger'));
                }
            }

            $data['detail'] = $this->User_model->All_data($this->VEEVA_Employee_ID);
            $data = array('title' => 'Profile Update', 'content' => 'User/Profile_Update', 'view_data' => $data, 'backUrl' => 'User/dashboard');
            $this->load->view('template2', $data);
        } else {
            $this->logout();
        }
    }

    public function BDM_Report() {
        if ($this->is_logged_in('BDM')) {
            $data['detail'] = $this->User_model->bdm_doctor_rx($this->VEEVA_Employee_ID, $this->nextMonth, $this->nextYear);
            $data = array('title' => 'Profile Update', 'content' => 'User/BDM_Report', 'view_data' => $data);
            $this->load->view('template2', $data);
        } else {
            $this->logout();
        }
    }

    public function sendMail2() {
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
                $mail->AddAddress($emp['Email_ID'], "BI-Tracking");

                $mail->Subject = "Forgot Password";

                $mail->IsHTML(true);

                $mail->Body = <<<EMAILBODY

Link For Reseting Password <br/>{$link}
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

    public function forget_pass() {
        $data = array('title' => 'forget', 'content' => 'User/forget', 'view_data' => 'blank');
        $this->load->view('template1', $data);
    }

    public function Reset_Password() {
        $data = array();
        if ($this->input->get('e')) {
            $id = $this->input->get('e');
            $id1 = base64_decode($id);
            $data['VEEVA_Employee_ID'] = $id1;
        }

        if ($this->input->post()) {
            $new = $this->Encryption->encode($this->input->post('password'));
            $id1 = $this->input->post('VEEVA_Employee_ID');
            $data2 = array(
                'VEEVA_Employee_ID' => $id1,
                'password' => $new,
                'password_status' => 'Active'
            );
            $this->User_model->Update_password($id1, $data2);
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Password Changed Successfully.', 'success'));
            redirect('User/index', 'refresh');
        }

        $data = array('title' => 'Reset_Password', 'content' => 'User/reset_password', 'view_data' => $data);
        $this->load->view('template1', $data);
    }

    public function DeletePriority() {
        if ($this->input->get('id') != '') {
            $Doctor_Id = $this->input->get('id');
            if (isset($this->VEEVA_Employee_ID) && $this->VEEVA_Employee_ID != '' && $this->Product_Id > 0 && $this->nextMonth > 0) {
                $this->db->delete('Actual_Doctor_Priority', array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                    'Product_Id' => $this->Product_Id,
                    'Doctor_Id' => $Doctor_Id,
                    'month' => $this->nextMonth,
                    'Year' => $this->nextYear));
            }
        }

        redirect('User/Priority', 'refresh');
    }

    public function BinaryBackup() {
        $type = $this->input->get('Type');
        $tables = array();
        $tablenames = $this->db->list_tables();

        $query = '';

        foreach ($tablenames as $table) {
            if (!empty($table)) {
                $tableName = $table;
                $backupFile = FCPATH . '\\assets\\backup\\' . $table . '.sql';
                if (file_exists($backupFile)) {
                    unlink($backupFile);
                }
                //echo $backupFile;
                $backupFile = str_replace("\\", "\\\\", $backupFile);
                //echo $backupFile;
                if ($type == "Backup") {
                    $query = "SELECT * INTO OUTFILE '$backupFile' FROM $tableName";
                } elseif ($query == 'restore') {
                    $query = "LOAD DATA INFILE  '$backupFile' FROM $tableName";
                }
                //echo $query;

                $result = $this->db->query($query);
            }
        }
    }

    public function codeigniterBkp() {
        ini_set('memory_limit', '512M');
        // Load the DB utility class
        $this->load->dbutil();

        // Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup();

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(FCPATH . 'assets/backup/mybackup.gz', $backup);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download('mybackup.gz', $backup);
    }

    public function ajax_data() {
        if ($this->Cycle == 1) {
            $view = $this->User_model->cycle2Activity($this->VEEVA_Employee_ID, $this->Product_Id, 1, 2, 3, 4);
        }
        if ($this->Cycle == 2) {
            $view = $this->User_model->cycle2Activity($this->VEEVA_Employee_ID, $this->Product_Id, 5, 6, 7, 8);
        }
        if ($this->Cycle == 3) {
            $view = $this->User_model->cycle2Activity($this->VEEVA_Employee_ID, $this->Product_Id, 9, 10, 11, 12);
        }
    }

    /* ~~~~ SCHEDULERS~~~~~ */

    public function maxRx() {
        $rxarray = array();
        $doctorArray = array();
        $Product_Id = $_GET['Product_Id'];
        $Month = date('n', strtotime('-1 day'));
        $Year = date('Y', strtotime('-1 day'));
        if (isset($_GET['Product_Id']) && $_GET['Product_Id'] > 0) {
            $sql = "SELECT MAX(Actual_Rx) AS Actual_Rx,Doctor_Id,Account_Name,VEEVA_Employee_ID FROM (
                        SELECT SUM(`Actual_Rx`) AS Actual_Rx,`Doctor_Id`,Account_Name,`VEEVA_Employee_ID` FROM `Rx_Actual` INNER JOIN `doctor_master` d ON d.`Account_ID` = Doctor_Id WHERE `Product_Id` = {$Product_Id} AND YEAR = '$Year' AND MONTH = {$Month} GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`
                    ) AS maxrx GROUP BY maxrx.`Doctor_Id` ";
            //echo $sql;
            $result = $this->db->query($sql)->result();
            if (!empty($result)) {
                foreach ($result as $rx) {
                    $rxarray[$rx->VEEVA_Employee_ID . "," . $rx->Doctor_Id] = $rx->Actual_Rx;
                    array_push($doctorArray, "'" . $rx->Doctor_Id . "'");
                }

                $updateSql = 'UPDATE Rx_Actual ';
                $updateSql .= "SET Max_Rx = CASE ";
                if (!empty($rxarray)) {
                    foreach ($rxarray as $key => $value) {
                        $explode = explode(",", $key);
                        $updateSql .= " WHEN YEAR = '$Year' AND MONTH = {$Month} AND Product_Id = '$Product_Id' AND Doctor_Id = '$explode[1]' THEN " . $value;
                    }
                    $updateSql .= " END ";
                }
                $updateSql .= ",Max_VEEVA_Employee_ID = CASE ";
                if (!empty($rxarray)) {
                    foreach ($rxarray as $key => $value) {
                        $explode = explode(",", $key);
                        $updateSql .= " WHEN YEAR = '$Year' AND MONTH = {$Month} AND Product_Id = '$Product_Id' AND Doctor_Id = '$explode[1]' THEN '" . $explode[0] . "' ";
                    }
                    $updateSql .= " END ";
                }
                $updateSql .=" WHERE Product_Id = '$Product_Id' AND YEAR = '$Year' AND MONTH = {$Month} AND Doctor_Id IN";
                $updateSql .="(" . join(",", $doctorArray) . ")";
                echo $updateSql;
                //$this->db->query($updateSql);
            }
        }
    }

    public function UpdateDailyRx() {
        $date = date('Y-m-d', strtotime('-1 day'));
        $sql = "UPDATE Rx_Actual SET Status = 'Submitted' WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = '$date' ";
        $this->db->query($sql);
    }

    public function updateActivityPlanning() {
        $date = date('n', strtotime('-1 month'));
        $year = date('Y', strtotime('-1 month'));
        $sql = "UPDATE Activity_Planning SET Status = 'Submitted' WHERE month = '$date' AND Year = '$year' ";
        $this->db->query($sql);
    }

    public function updateActivity() {
        $date = date('n', strtotime('-1 month'));
        $year = date('Y', strtotime('-1 month'));

        $sql = "UPDATE Activity_Reporting SET Status = 'Submitted' WHERE month = '$date' AND Year = '$year' ";
        $this->db->query($sql);
    }

    /* ~~~~~~  END OF SCHEDULERS ~~~~~~*/
    public function Doctorlist() {
        
        $data = array('title' => 'Customer List', 'content' => 'User/list', 'page_title' => 'Doctor List', 'view_data' => 'blicnk');
        $this->load->view('bdmfront', $data);
    }

    public function Doctorlist2() {
        if ($this->is_logged_in('BDM')) {
            $this->setProductId();
            $data['site_url'] = 'User/DoctorList2';
            $data['doctorlist'] = $this->Doctor_Model->getProfiledDoctor($this->VEEVA_Employee_ID, $this->Product_Id, $this->Individual_Type, $this->Cycle);
            $data = array('title' => 'Profiling', 'content' => 'User/list2', 'page_title' => 'Doctor List', 'view_data' => $data);
            $this->load->view('bdmfront', $data);
        } else {
            $this->logout();
        }
    }

    function monthlyTrend() {
        $offset = 0;
        $perpage = 5000;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $TerritoryConditions = array();
        $product = 0;
        $data['Territory'] = '';
        $data['result'] = NULL;

        ///Initial Product Dropdown
        $productlist = $this->admin_model->show_pro_list();
        $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));


        array_push($TerritoryConditions, "em.Profile = 'BDM' ");

        $condition = array();
        if ($this->input->get('Zone') != '') {
            $Zone = $this->Designation == 'ZSM' ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
            array_push($TerritoryConditions, "em.Zone = '" . $this->input->get('Zone') . "'");
        }

        if ($this->input->get('Division') != '') {
            $productlist = $this->Master_Model->BrandList($this->input->get('Division'));
            $Division = ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM' ) ? $this->Division : $this->input->get('Division');
            $condition[1] = "em.Division = '" . $Division . "'";
            array_push($TerritoryConditions, "em.Division = '" . $Division . "'");
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        }

        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[3] = "em.Territory = '" . $this->input->get('Territory') . "'";
        }

        if ($this->input->get('VEEVA_Employee_ID') && $this->input->get('VEEVA_Employee_ID') != '') {
            $condition[5] = "em.VEEVA_Employee_ID = '" . $this->input->get('VEEVA_Employee_ID') . "'";
        }
        ///Fetch Monthly Trend Data
        if ($this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
            $product = $this->input->get('Product');
            $ProfileCount = $this->User_model->countMonthlyTrend($product, $this->nextYear, $condition);
            //var_dump($ProfileCount);
            if (isset($ProfileCount->PlanningCount)) {
                $data['total_pages'] = ceil($ProfileCount->PlanningCount / $perpage);
                $offset = ($page - 1) * $perpage;
            }
            $data['result'] = $this->User_model->monthlyTrend2(1, $this->nextYear, $this->input->get('Product'), $condition, $perpage, $offset);
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        } elseif ($this->input->get('Product') == 'All') {
            array_push($this->Errors, 'Please Select Product');
            //$data['result'] = $this->User_model->monthlyTrend2(1, $this->nextYear, $this->input->get('Product'), $condition);
        } else {
            array_push($this->Errors, 'Please Select Product');
        }

        ///Generate Territory Dropdown
        $Territory = $this->User_model->getTerritory1($TerritoryConditions);
        $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));

        ///Display Errors
        if (!empty($this->Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($this->Errors))), 'danger');
        }

        ///EXPORT TO EXCEL ARRAY(Converting From Array To Object)
        $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
        $fields = array('Zone', 'Territory', 'BDM Name', 'BDM Code', 'Doctor Code', 'Doctor Name', 'Product Name', 'Activity Planned', 'Activity Completed', 'Rx Planned');
        for ($m = 1; $m <= 12; $m++) {
            $month = date('M', mktime(0, 0, 0, $m, 1, date('Y')));
            array_push($fields, $month);
        }
        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'MonthlyTrend', $fields);
        }
        $data = array('title' => 'Monthly Trend', 'content' => 'User/monthlyTrend', 'page_title' => 'Monthly Rx Trend', 'view_data' => $data);
        $this->load->view('bdmfront', $data);
    }

    /// setting Product Id For Tabs
    function setProductId() {
        if ($this->input->get('Product_Id') > 0) {
            $this->Product_Id = $this->input->get('Product_Id');
        } else {
            $Productlist = $this->Product_List;
            $firstProduct = array_shift($Productlist);
            $this->Product_Id = $firstProduct->id;
        }

        if ($this->Product_Id == 1) {
            $this->Individual_Type = 'Hospital';
            $this->alertLabel = 'Hospital';
        }
    }

    ///Calculating Planning CutOffDates
    function PlanCutOffDate() {
        $current_day = date('d');
        if ($current_day <= 3) {
            $current_month = date('n', strtotime('-1 month'));
            $created_at = date("Y-m-d", mktime(0, 0, 0, date("m"), 0, date("Y")));
        } else {
            $current_month = date('n');
            $created_at = date('Y-m-d H:i:s');
        }
        return array($current_month, $created_at);
    }

    ///Setting CutOffDates
    function setCutOffDate() {
        $result = $this->PlanCutOffDate();
        $this->nextMonth = $result[0];
        $this->nextYear = date('Y', strtotime($result[1]));
        return $result[1];
    }

    ///BDM Panel Redirects
    function redirects($type, $product_id = 0) {
        if ($product_id > 0) {
            switch ($type) {
                case 'Profiling':
                    redirect('User/DoctorList2?Product_Id=' . $product_id, 'refresh');
                    break;
                case 'Planning':
                    redirect('User/Planning?Product_Id=' . $product_id, 'refresh');
                    break;
                case 'ActivityPlanning':
                    redirect('User/ActivityPlanning?Product_Id=' . $product_id, 'refresh');
                    break;
                case 'Reporting':
                    redirect('User/Reporting?Product_Id=' . $product_id, 'refresh');
                    break;
                case 'ActivityReporting':
                    redirect('User/Profiling?Product_Id=' . $product_id, 'refresh');
                    break;

                default:
                    redirect('User/dashboard?Product_Id=' . $product_id, 'refresh');
                    break;
            }
        } else {
            redirect('User/dashboard', 'refresh');
        }
    }

}
