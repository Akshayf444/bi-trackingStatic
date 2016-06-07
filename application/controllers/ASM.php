<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ASM extends MY_Controller {

    public $alertLabel = 'ASM';

    public function __construct() {
        parent::__construct();
        $this->load->helper();
        $this->load->model('User_model');
        $this->load->model('asm_model');
        $this->load->model('Encryption');
        $this->load->model('Master_Model');
        $this->load->model('Doctor_Model');
        $this->load->library('form_validation');
        $this->calcPlanning();
    }

    function calcPlanning() {
        $this->setCutOffDate();
    }

    public function dashboard() {
        if ($this->is_logged_in('ASM')) {

            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['productlist2'] = $result2;
            $result2 = array_shift($result2);
            $object = new stdClass();
            $object->id = $result2->id;
            $object->Brand_Name = $result2->Brand_Name;
            $productlist = array($object);
            $data['productlist'] = $productlist;

            $data = array('title' => 'Main', 'content' => 'ASM/ASM_dashboard', 'page_title' => 'Dashboard', 'view_data' => $data);
            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function Planning() {
        if ($this->is_logged_in('ASM')) {
            $data = array('title' => 'Planning', 'content' => 'ASM/asm_planning', 'backUrl' => 'ASM/dashboard', 'view_data' => 'blank');
            $this->load->view('asmfront', $data);
        }
    }

    public function target() {
        if ($this->is_logged_in('ASM')) {
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name');
            if ($this->input->post()) {
                $this->Product_Id = $this->input->post('product_id');
                $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name', $this->Product_Id);
                $data['result'] = $this->asm_model->getTarget();
            }
            $check = $this->User_model->ASM_division($this->VEEVA_Employee_ID);
            if (!empty($check)) {
                if (strtolower($check['division']) == 'diabetes') {
                    $data['table'] = $this->asm_model->ASm($this->VEEVA_Employee_ID);
                    $data['ck'] = "Diabetes";
                } elseif (strtolower($check['division']) == 'thrombi') {
                    $data['table'] = $this->asm_model->ASm($this->VEEVA_Employee_ID);
                    $data['ck'] = "ThromBI";
                }
            }
            $data = array('title' => 'Target', 'content' => 'ASM/target', 'backUrl' => 'ASM/dashboard', 'page_title' => 'Assign Target', 'view_data' => $data);
            $this->load->view('asmfront', $data);
        }
    }

    public function reporting() {
        if ($this->is_logged_in('ASM')) {
            $data = array('title' => 'Planning', 'content' => 'ASM/Asm_Reporting', 'backUrl' => 'ASM/dashboard', 'view_data' => 'blank');
            $this->load->view('asmfront', $data);
        }
    }

    public function asm_rx_planning() {
        if ($this->is_logged_in('ASM')) {
            $id2 = $this->session->userdata('VEEVA_Employee_ID');
            $result = $this->asm_model->rx_view($id2);

            //BDM List
            $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name');
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));

            //ProductList Dropdown
            $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name');
            $data['productlist'] = $result2;

            //POST ACTION
            if ($this->input->post()) {
                $product = $this->input->post('product_id');
                $id = $this->input->post('rx_id');

                $result = $this->asm_model->rx_view($id2);
                $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name', $id);
                $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
                $data['productlist'] = NULL;
                $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name', $product);
                $data['show'] = $this->User_model->getPlanningAproval($id, $product, $this->nextMonth);
            }
            $data = array('title' => 'Report', 'content' => 'ASM/Asm_rxplanning', 'page_title' => 'Approve Planning', 'backUrl' => 'ASM/dashboard', 'view_data' => $data);
            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function approveTarget() {
        $logmessage = array();
        //var_dump($_POST);
        $targetAdded = 0;
        $targetUpdated = 0;
        $targetAssigned = 0;

        ///AJAX POST ACTION ....
        if ($this->input->post()) {
            $VEEVA_Employee_ID = $this->input->post('VEEVA_Employee_ID');
            $target1 = $this->input->post('target1');
            $target2 = $this->input->post('target2');
            $target3 = $this->input->post('target3');
            $Status = $this->input->post('Status');
            //var_dump($this->input->post('Status'));
            for ($i = 0; $i < count($this->input->post('VEEVA_Employee_ID')); $i++) {

                if ($this->Division == 'Diabetes') {
                    $product_ids = array(4, 5, 6);
                    $count = 1;
                    foreach ($product_ids as $id) {
                        $data1 = array(
                            'target' => ${'target' . $count}[$i],
                            'VEEVA_Employee_ID' => $VEEVA_Employee_ID[$i],
                            'Product_Id' => $id,
                            'Month' => $this->nextMonth,
                            'Year' => $this->nextYear,
                            'Status' => $this->input->post('Status'),
                        );

                        $check = $this->User_model->Set_Target_by_id($VEEVA_Employee_ID[$i], $id, $this->nextMonth);
                        if (empty($check)) {
                            $data1['created_at'] = date('Y-m-d H:i:s');
                            $this->User_model->Set_Target($data1);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Added Successfully.', 'success'));
                            array_push($logmessage, 'New Rx Target Added.');
                            $targetAdded ++;
                        } elseif ($check['Status'] == 'Draft') {
                            $data1['updated_at'] = date('Y-m-d H:i:s');
                            $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID[$i], 'Product_Id' => $id, 'month' => $this->nextMonth));
                            $this->db->update('Rx_Target', $data1);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Updated Successfully.', 'success'));
                            array_push($logmessage, 'New Rx Target Updated.');
                            $targetUpdated++;
                        } elseif ($check['Status'] == 'Submitted') {
                            $targetAssigned ++;
                            array_push($logmessage, 'New Rx Target Already Assigned.');
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Already Assigned.', 'danger'));
                        }
                        $count++;
                    }
                } elseif (strtolower($this->Division) == 'thrombi') {
                    $product_ids = array(1, 2, 3);
                    $count = 1;
                    foreach ($product_ids as $id) {
                        $data1 = array(
                            'target' => ${'target' . $count}[$i],
                            'VEEVA_Employee_ID' => $VEEVA_Employee_ID[$i],
                            'Product_Id' => $id,
                            'Month' => $this->nextMonth,
                            'Year' => $this->nextYear,
                            'Status' => $Status,
                        );

                        $check = $this->User_model->Set_Target_by_id($VEEVA_Employee_ID[$i], $id, $this->nextMonth);
                        if (empty($check)) {
                            $data1['created_at'] = date('Y-m-d H:i:s');
                            $this->User_model->Set_Target($data1);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Added Successfully.', 'success'));
                            array_push($logmessage, 'New Rx Target Added.');
                            $targetAdded ++;
                        } elseif ($check['Status'] == 'Draft') {
                            $data1['updated_at'] = date('Y-m-d H:i:s');
                            $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID[$i], 'Product_Id' => $id, 'month' => $this->nextMonth));
                            $this->db->update('Rx_Target', $data1);
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Updated Successfully.', 'success'));
                            array_push($logmessage, 'New Rx Target Updated.');
                            $targetUpdated++;
                        } elseif ($check['Status'] == 'Submitted') {
                            $targetAssigned ++;
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Already Assigned.', 'danger'));
                            array_push($logmessage, 'New Rx Target Already Assigned.');
                        }
                        $count++;
                    }
                }
            }

            $targetAdded = $targetAdded / 3;
            $targetUpdated = $targetUpdated / 3;
            $targetAssigned = $targetAssigned / 3;

            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Target Added For ' . $targetAdded . ' BDM , Target Updated For ' . $targetUpdated . ' BDM , Target Already Assigned For ' . $targetAssigned . ' BDM', 'success'));
            $message = join(",", array_unique($logmessage));
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ASM',
            );
            $this->User_model->insertLog($logdata);
        }
        //header('Location:' . site_url('ASM/target'));
    }

    public function ApprovePlanning() {
        if ($this->input->post()) {
            $approveCount = 0;
            $rejectCount = 0;
            for ($i = 0; $i < count($this->input->post('Doctor_Id')); $i++) {
                $doctorId = $this->input->post('Doctor_Id');
                $data = array(
                    'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                );
                if ($this->input->post('approve_' . $doctorId[$i])) {
                    $data['Approve_Status'] = $this->input->post('approve_' . $doctorId[$i]);
                } else {
                    $data['Approve_Status'] = 'SFA';
                }

                $data['field_changed'] = 0;
                $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => $this->input->post('product'), 'month' => (int) date('n')));
                $this->db->update('Rx_Planning', $data);
                if ($data['Approve_Status'] == 'Approved') {
                    $approveCount++;
                } elseif ($data['Approve_Status'] == 'Un-Approved') {
                    $rejectCount++;
                }
            }

            $comment = array(
                'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                'created_at' => date('Y-m-d H:i:s'),
                'Comment' => $this->input->post('Comment'),
                'Comment_type' => 'Planning',
                'Product_Id' => $this->input->post('product'),
                'Reporting_Id' => $this->VEEVA_Employee_ID
            );

            $this->asm_model->insertComment($comment);

            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($approveCount . ' Records  Approved And ' . $rejectCount . ' Records  Rejected', 'success'));
            $message = 'Rx Planning Approval :' . $approveCount . ' Records  Approved And ' . $rejectCount . ' Records  Rejected';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ASM',
            );
            $this->User_model->insertLog($logdata);
            redirect('ASM/asm_rx_planning', 'refresh');
        }
    }

    //Controller for Activity Planning List
    public function activity_planning() {
        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        if ($this->is_logged_in('ASM')) {
            $id2 = $this->session->userdata('VEEVA_Employee_ID');
            $result = $this->asm_model->rx_view($id2);
            //BDM List
            $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name');

            //Productlist
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));

            //Generate Productlist Dropdown
            $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name');
            $data['productlist'] = $result2;
            $data['current_month'] = $current_month;
            //POST ACTION
            if ($this->input->post()) {
                $product = $this->input->post('product_id');
                $id = $this->input->post('rx_id');
                $this->Product_Id = $product;

                //BDM List
                $result = $this->asm_model->rx_view($id2);
                $data['productlist'] = NULL;
                //BDM Dropdown
                $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name', $id);
                $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));

                $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name', $product);
                $result = $this->User_model->getActivityDoctor2($id, $product, $current_month);
                $data['Doctorlist'] = $this->User_model->generateActivityTable2($result);
            }

            $data = array('title' => 'Report', 'content' => 'ASM/activity_planning', 'page_title' => 'Approve Activity Planning', 'backUrl' => 'ASM/dashboard', 'view_data' => $data);

            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    //Approve Activity Planning
    public function ApproveActivity() {
        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        if ($this->input->post()) {
            //var_dump($_POST);
            $approveCount = 0;
            $rejectCount = 0;
            for ($i = 0; $i < count($this->input->post('Doctor_Id')); $i++) {
                $doctorId = $this->input->post('Doctor_Id');
                $data = array(
                    'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                    'field_changed' => 0
                );

                if ($this->input->post('approve_' . $doctorId[$i])) {
                    $data['Approve_Status'] = $this->input->post('approve_' . $doctorId[$i]);
                } else {
                    $data['Approve_Status'] = 'SFA';
                }

                //Conditions for approving both entries for trajenta and trajenta duo
                if ($this->input->post('product') == 4 || $this->input->post('product') == 6) {
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => 4, 'month' => (int) $current_month));
                    $this->db->update('Activity_Planning', $data);
                    //echo $this->db->last_query();
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => 6, 'month' => (int) $current_month));
                    $this->db->update('Activity_Planning', $data);
                } else {
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => $this->input->post('product'), 'month' => (int) $current_month));
                    $this->db->update('Activity_Planning', $data);
                    //echo $this->db->last_query();
                }

                if ($data['Approve_Status'] == 'Approved') {
                    $approveCount++;
                } elseif ($data['Approve_Status'] == 'Un-Approved') {
                    $rejectCount++;
                }
            }

            $comment = array(
                'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                'created_at' => date('Y-m-d H:i:s'),
                'Comment' => $this->input->post('Comment'),
                'Comment_type' => 'Activity_Planning',
                'Product_Id' => $this->input->post('product'),
                'Reporting_Id' => $this->VEEVA_Employee_ID
            );

            if ($this->input->post('product') == 4 || $this->input->post('product') == 6) {
                $comment['Product_Id'] = 4;
                $this->asm_model->insertComment($comment);
                $comment['Product_Id'] = 6;
                $this->asm_model->insertComment($comment);
            } else {
                $this->asm_model->insertComment($comment);
            }


            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($approveCount . ' Activities  Approved And ' . $rejectCount . ' Activities  Rejected', 'success'));
            $message = 'Activity Planning Approval :' . $approveCount . ' Activities  Approved And ' . $rejectCount . ' Activities  Rejected ';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ASM',
            );
            $this->User_model->insertLog($logdata);
            redirect('ASM/activity_planning', 'refresh');
        }
    }

    public function reporting_rx() {
        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        $data['month'] = $this->Master_Model->generateDropdown($this->User_model->getMonthObject(), 'month', 'monthname');
        if (isset($_POST['month'])) {
            $current_month = (int) $_POST['month'];
            $data['month'] = $this->Master_Model->generateDropdown($this->User_model->getMonthObject(), 'month', 'monthname', $current_month);
        }

        if ($this->is_logged_in('ASM')) {
            $id2 = $this->session->userdata('VEEVA_Employee_ID');
            $result = $this->asm_model->rx_view($id2);
            $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name');
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name');
            $data['productlist'] = $result2;
            if ($this->input->post()) {
                $product = $this->input->post('product_id');
                $id = $this->input->post('rx_id');
                $this->Product_Id = $product;
                $result = $this->asm_model->rx_view($id2);
                $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name', $id);
                $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
                $data['productlist'] = NULL;
                $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name', $product);
                $data['show'] = $this->User_model->getReporting2($id, $product, $current_month);
            }

            $data = array('title' => 'Report', 'content' => 'ASM/reporting_rx', 'page_title' => 'Approve Rx/Vials Reporting', 'backUrl' => 'ASM/dashboard', 'view_data' => $data);

            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function Approvereporting() {
        if ($this->input->post()) {
            $approveCount = 0;
            $rejectCount = 0;
            for ($i = 0; $i < count($this->input->post('Rxplan_id')); $i++) {
                $empid = $this->input->post('approve');
                $rxreport = $this->input->post('Rxplan_id');
                $doctorId = $this->input->post('Doctor_Id');
                $data = array(
                    'Approve_Status' => 'Approved',
                    'field_changed' => 0
                );

                if ($this->input->post('approve_' . $doctorId[$i])) {
                    $data['Approve_Status'] = $this->input->post('approve_' . $doctorId[$i]);
                } else {
                    $data['Approve_Status'] = 'SFA';
                }
                $rxplanids = explode(",", $rxreport[$i]);
                if (!empty($rxplanids)) {
                    foreach ($rxplanids as $Rxplan_id) {
                        $this->db->where(array('Rxplan_id' => $Rxplan_id));
                        $this->db->update('Rx_Actual', $data);
                    }
                }
                if ($data['Approve_Status'] == 'Approved') {
                    $approveCount++;
                } elseif ($data['Approve_Status'] == 'Un-Approved') {
                    $rejectCount++;
                }
            }

            $comment = array(
                'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                'created_at' => date('Y-m-d H:i:s'),
                'Comment' => $this->input->post('Comment'),
                'Comment_type' => 'Reporting',
                'Product_Id' => $this->input->post('product'),
                'Reporting_Id' => $this->VEEVA_Employee_ID
            );

            $this->asm_model->insertComment($comment);

            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($approveCount . ' Records  Approved And ' . $rejectCount . ' Records  Rejected', 'success'));
            $message = 'Rx Reporting Approval :' . $approveCount . ' Records  Approved And ' . $rejectCount . ' Records  Rejected';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ASM',
            );
            $this->User_model->insertLog($logdata);
            redirect('ASM/reporting_rx', 'refresh');
        }
    }

    public function Approve_reporting_Activity() {
        //var_dump($_POST);
        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        if ($this->input->post()) {
            $approveCount = 0;
            $rejectCount = 0;
            for ($i = 0; $i < count($this->input->post('Doctor_Id')); $i++) {
                $doctorId = $this->input->post('Doctor_Id');
                $data = array(
                    'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                    'field_changed' => 0
                );
                //Approve Both the entries Trajenta and Trajenta Duo
                //$data['Approve_Status'] = $this->input->post('approve_' . $doctorId[$i]);
                if ($this->input->post('approve_' . $doctorId[$i])) {
                    $data['Approve_Status'] = $this->input->post('approve_' . $doctorId[$i]);
                } else {
                    $data['Approve_Status'] = 'SFA';
                }
                if ($this->input->post('product') == 4 || $this->input->post('product') == 6) {
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => 4, 'month' => (int) $current_month));
                    $this->db->update('Activity_Reporting', $data);
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => 6, 'month' => (int) $current_month));
                    $this->db->update('Activity_Reporting', $data);
                } else {
                    $this->db->where(array('VEEVA_Employee_ID' => $this->input->post('BDM_ID'), 'Doctor_Id' => $doctorId[$i], 'Product_Id' => $this->input->post('product'), 'month' => (int) $current_month));

                    $this->db->update('Activity_Reporting', $data);
                    ///echo $this->db->last_query();
                }
                if ($data['Approve_Status'] == 'Approved') {
                    $approveCount++;
                } elseif ($data['Approve_Status'] == 'Un-Approved') {
                    $rejectCount++;
                }
            }

            $comment = array(
                'VEEVA_Employee_Id' => $this->input->post('BDM_ID'),
                'created_at' => date('Y-m-d H:i:s'),
                'Comment' => $this->input->post('Comment'),
                'Comment_type' => 'Activity_Reporting',
                'Product_Id' => $this->input->post('product'),
                'Reporting_Id' => $this->VEEVA_Employee_ID
            );

            if ($this->input->post('product') == 4 || $this->input->post('product') == 6) {
                $comment['Product_Id'] = 4;
                $this->asm_model->insertComment($comment);
                $comment['Product_Id'] = 6;
                $this->asm_model->insertComment($comment);
            } else {
                $this->asm_model->insertComment($comment);
            }


            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert($approveCount . ' Activities  Approved And ' . $rejectCount . ' Activities  Rejected', 'success'));
            $message = 'Activity Reporting Approval :' . $approveCount . ' Activities  Approved And ' . $rejectCount . ' Activities  Rejected';
            $logdata = array(
                'date' => date('Y-m-d H:i:s'),
                'description' => $message,
                'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                'ip_address' => $this->input->ip_address(),
                'Profile' => 'ASM',
            );
            $this->User_model->insertLog($logdata);
            redirect('ASM/reporting_activity', 'refresh');
        }
    }

    public function reporting_activity() {
        $cutoffdates = $this->User_model->CutOfDate();
        $current_month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        $data['month'] = $this->Master_Model->generateDropdown($this->User_model->getMonthObject(), 'month', 'monthname');
        if (isset($_POST['month'])) {
            $current_month = (int) $_POST['month'];
            $data['month'] = $this->Master_Model->generateDropdown($this->User_model->getMonthObject(), 'month', 'monthname', $current_month);
        }

        $data['current_month'] = $current_month;
        if ($this->is_logged_in('ASM')) {
            $id2 = $this->session->userdata('VEEVA_Employee_ID');
            $result = $this->asm_model->rx_view($id2);
            $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name');
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name');
            $data['productlist'] = $result2;
            if ($this->input->post()) {
                $product = $this->input->post('product_id');
                $id = $this->input->post('rx_id');

                $this->Product_Id = $product;
                $result = $this->asm_model->rx_view($id2);

                //BDM Dropdown
                $data['bdm'] = $this->Master_Model->generateDropdown($result, 'VEEVA_Employee_ID', 'Full_Name', $id);
                $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
                //Product List
                $data['product'] = $this->Master_Model->generateDropdown($result2, 'id', 'Brand_Name', $product);

                $data['productlist'] = NULL;
                $result = $this->User_model->getPlannedActivityDoctor2($id, $product, $current_month);
                $data['Doctorlist'] = $this->User_model->generateActivityTable2($result, 'Reporting');
            }

            $data = array('title' => 'Report', 'content' => 'ASM/reporting_activity', 'page_title' => 'Approve Activity reporting', 'backUrl' => 'ASM/dashboard', 'view_data' => $data);

            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    //Status Table For Rx Reporting
    public function getApprovedStatusCount() {
        $cutoffdates = $this->User_model->CutOfDate();
        $month = $cutoffdates[0];
        $created_at = $cutoffdates[1];

        $productlist = $this->Master_Model->BrandList($this->session->userdata('Division'));
        ?>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <?php if (!empty($productlist)) { ?>
                <div class="panel panel-default"> 
                    <div class="panel-heading"> Status  </div>
                    <div class="panel-body">

                        <ul align="center" class="nav nav-tabs ">
                            <?php
                            if (!empty($productlist)) {
                                $count = 1;
                                foreach ($productlist as $product) {
                                    ?>
                                    <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" href="#<?php echo $product->id ?>"><?php echo $product->Brand_Name ?></a></li>
                                    <?php
                                    $count ++;
                                }
                            }
                            ?>
                        </ul>

                        <div class="tab-content">
                            <?php
                            if (!empty($productlist)) {
                                $count = 1;
                                foreach ($productlist as $product) {
                                    $ApproveCount = 0;
                                    $UnApproveCount = 0;
                                    $Pending = 0;
                                    $Submitted = 0;
                                    ?>

                                    <div id="<?php echo $product->id ?>" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>BDM Name</th>
                                                <th>Approved</th>
                                                <th>Rejected</th>
                                                <th>Pending</th>
                                                <th>Submitted By BDM Post ASM Approval</th>
                                            </tr>
                                            <?php
                                            $BDM = $this->asm_model->rx_view($this->VEEVA_Employee_ID);
                                            foreach ($BDM as $item) {
                                                $Status = $this->asm_model->RxReportingStatus($product->id, $item->VEEVA_Employee_ID, $month);
                                                if (!empty($Status)) {
                                                    foreach ($Status as $value) {
                                                        $ApproveCount += $value->ApproveCount;
                                                        $UnApproveCount += $value->UnApproveCount;
                                                        $Pending += $value->SFACount;
                                                        $Submitted += $value->SubmitCount;
                                                        echo '<tr><td>' . $item->Full_Name . '</td><td>' . $value->ApproveCount . '</td><td>' . $value->UnApproveCount . '</td><td>' . $value->SFACount . '</td><td>' . $value->SubmitCount . '</td></tr>';
                                                    }
                                                }
                                            } echo '<tr><th>Total</th><td>' . $ApproveCount . '</td><td>' . $UnApproveCount . '</td><td>' . $Pending . '</td><td>' . $Submitted . '</td></tr>';
                                            ?>
                                        </table>
                                    </div>


                                    <?php
                                    $count ++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>  
            <?php } ?>
        </div><?php
    }

    public function ASM_update() {
        if ($this->is_logged_in('ASM')) {
            if ($this->input->post()) {
                $number = $this->input->post('mobile');
                $date = $this->input->post('date');
                $date1 = date('Y-m-d', strtotime($date));
                $mobile = array('Mobile' => $number, 'DOB' => $date1);
                $mob = $this->User_model->Update_mobile($this->VEEVA_Employee_ID, $mobile);
                $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Update Successfully.', 'success'));
            }
            $data['detail'] = $this->User_model->All_data($this->VEEVA_Employee_ID);
            $data = array('title' => 'Profile Update', 'content' => 'ASM/Profile_Update', 'view_data' => $data, 'backUrl' => 'ASM/dashboard');
            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function pwd_update() {
        if ($this->is_logged_in('ASM')) {
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
                        } else {
                            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert('Old Password Not Match .', 'danger'));
                        }
                    }
                } else {
                    $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(' Cannot Use Already  Used Password .', 'danger'));
                }
                $message = $this->session->userdata('message');
                $logdata = array(
                    'date' => date('Y-m-d H:i:s'),
                    'description' => $message,
                    'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID,
                    'ip_address' => $this->input->ip_address(),
                    'Profile' => 'ASM',
                );
                $this->User_model->insertLog($logdata);
            }

            $data['detail'] = $this->User_model->All_data($this->VEEVA_Employee_ID);
            $data = array('title' => 'Profile Update', 'content' => 'ASM/Profile_Update', 'view_data' => $data, 'backUrl' => 'ASM/dashboard');
            $this->load->view('asmfront', $data);
        } else {
            $this->logout();
        }
    }

    public function reporting_info() {

        $data['show'] = $this->asm_model->hospital_list($this->session->userdata('VEEVA_Employee_ID'));
        $data = array('title' => 'Hospital List', 'content' => 'ASM/ActilyseReport','page_title'=>'Hospital List', 'view_data' => $data, 'backUrl' => 'ASM/dashboard');
        $this->load->view('asmfront', $data);
    }

    function data_show() {
        $id = $this->input->get('id');
        $data['list'] = $this->asm_model->data_report($id);
        $data = array('title' => 'Hospital List', 'content' => 'ASM/asm_reporting_tab', 'view_data' => $data, 'backUrl' => 'ASM/dashboard');
        $this->load->view('asmfront', $data);
    }

    public function decryptPassword() {
        $this->load->model('Encryption');
        $sql = "SELECT * FROM Employee_Master where VEEVA_Employee_ID = 'ADMIN 1' ";
        $query = $this->db->query($sql);
        $result = $query->row();
        echo $this->Encryption->decode($result->password);
    }

}
