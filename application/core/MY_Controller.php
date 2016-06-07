<?php

class MY_Controller extends CI_Controller {

    public $VEEVA_Employee_ID;
    public $Local_Employee_ID;
    public $Designation;
    public $Reporting_To;
    public $Reporting_VEEVA_ID;
    public $Reporting_Local_ID;
    public $Division;
    public $Full_Name;
    public $table_name;
    public $Product_Id = 0;
    public $nextMonth;
    public $nextYear;
    public $Individual_Type;
    public $message;
    public $password_status;
    public $Zone;
    public $Cycle;
    public $Product_List;
    public $Territory;

    function __construct() {
        parent::__construct();
        $this->VEEVA_Employee_ID = $this->session->userdata('VEEVA_Employee_ID');
        $this->Local_Employee_ID = $this->session->userdata('Local_Employee_ID');
        $this->Reporting_To = $this->session->userdata('Reporting_To');
        $this->Reporting_VEEVA_ID = $this->session->userdata('Reporting_VEEVA_ID');
        $this->Reporting_Local_ID = $this->session->userdata('Reporting_Local_ID');
        $this->Designation = $this->session->userdata('Designation');
        $this->Division = $this->session->userdata('Division');
        $this->Full_Name = $this->session->userdata('Full_Name');
        $this->password_status = $this->session->userdata('password_status');
        $this->Zone = $this->session->userdata('Zone');
        $this->nextMonth = date('n');
        $this->nextYear = date('Y');
        $this->Territory = $this->session->userdata('Territory');
        if (date('n') == 1 || date('n') == 2 || date('n') == 3 || date('n') == 4) {
            $this->Cycle = 1;
        } elseif (date('n') == 5 || date('n') == 6 || date('n') == 7 || date('n') == 8) {
            $this->Cycle = 2;
        } elseif (date('n') == 9 || date('n') == 10 || date('n') == 11 || date('n') == 12) {
            $this->Cycle = 3;
        }

        if ($this->Product_Id == 1 || $this->input->get('Product_Id') == 1) {
            $this->Individual_Type = 'Hospital';
        } else {
            $this->Individual_Type = 'Doctor';
        }
        $this->Cycle = 2;
    }

    function is_logged_in($Profile = "") {
        if (!is_null($this->session->userdata('VEEVA_Employee_ID')) && $this->session->userdata('VEEVA_Employee_ID') != '' && !is_null($this->session->userdata('VEEVA_Employee_ID')) && !is_null($this->password_status) && $this->password_status != '') {
            if (strtolower($this->session->userdata('Designation')) == strtolower($Profile)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function logout() {
        $this->session->unset_userdata('VEEVA_Employee_ID');
        $this->session->unset_userdata('Local_Employee_ID');
        $this->session->unset_userdata('Designation');
        $this->session->unset_userdata('Reporting');
        $this->session->unset_userdata('Reporting_VEEVA_ID');
        $this->session->unset_userdata('Reporting_Local_ID');
        $this->session->unset_userdata('Product_Id');
        $this->session->unset_userdata('Division');
        $this->session->unset_userdata('Zone');
        $this->session->unset_userdata('password_status');
        $this->VEEVA_Employee_ID = null;
        $this->Local_Employee_ID = null;
        $this->Designation = null;
        $this->Reporting_To = null;
        $this->Reporting_VEEVA_ID = null;
        $this->Reporting_Local_ID = null;
        $this->Division = null;
        $this->Full_Name = null;
        $this->table_name = null;
        $this->Product_Id = null;
        $this->nextMonth = null;
        $this->nextYear = null;
        $this->Zone = null;
        $this->password_status = null;
        redirect('User/index', 'refresh');
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

}
