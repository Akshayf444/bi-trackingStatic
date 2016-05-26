<?php

class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->table_name = 'Employee_Master';
    }

    public function authentication($username, $password) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where(array('Username' => $username, 'Password' => $password, 'Status' => '1'));
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row_array();
    }

    public function profiling_by_id($Doctor_id, $VEEVA_Employee_ID, $Product_id, $Cycle) {
        $this->db->select('*');
        $this->db->from('Profiling');
        $this->db->where(array('Doctor_id' => $Doctor_id, 'VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'Product_id' => $Product_id, 'Cycle' => $Cycle));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function Set_Target($data) {
        return $this->db->insert('Rx_Target', $data);
    }

    public function Set_Target_update2($data) {
        $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id));
        return $this->db->update('Rx_Target', $data);
    }

    public function Set_Target_by_id($id, $pid, $month) {
        $sql = "select * from Rx_Target
                where VEEVA_Employee_ID='$id' And Product_Id='$pid' And Month=$month";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function Rx_Target_month($VEEVA_Employee_ID, $Product_Id, $month_start, $year) {
        $sql = "SELECT * FROM Rx_Target
                WHERE Month = $month_start
                AND `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND `Product_Id`= '$Product_Id' And Year='$year' AND Status = 'Submitted' ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row();
    }

    public function Actual_Rx_Target_month($VEEVA_Employee_ID, $Product_Id, $month, $year) {
        $sql = "SELECT SUM(Actual_Rx) as Act FROM Rx_Actual
                WHERE month=$month
                AND `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND `Product_Id`=$Product_Id  And Year=$year";
        //echo $sql.'<br>';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Rx_Target_month2($VEEVA_Employee_ID, $Product_Id, $month_start) {
        $sql = "SELECT *  FROM Rx_Target
                WHERE Month = $month_start
                AND `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND `Product_Id`=$Product_Id And Year='$this->nextYear' AND Status = 'Submitted'  ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function Expected_Rx($id, $pid, $month) {
        $this->db->select('target');
        $this->db->from(' Rx_Target');
        $this->db->where(array('month' => $month, 'VEEVA_Employee_ID' => $id, 'Product_Id' => $pid));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function Save_Planning($data) {
        $this->db->insert('Rx_Planning', $data);
        return $this->db->insert_id();
    }

    public function Save_Planning_prescription($data, $id, $doc_id, $pid, $month = 0, $year = 0) {
        $this->db->where(array('VEEVA_Employee_ID' => $id, 'Doctor_Id' => $doc_id, 'Product_Id' => $pid, 'month' => $month, 'Year' => $year));
        return $this->db->update('Rx_Planning', $data);
    }

    public function Set_Target_update($id, $data, $Pid) {
        $this->db->where(array('VEEVA_Employee_ID' => $id, 'Product_Id' => $Pid));
        return $this->db->update('Rx_Target', $data);
    }

    public function Tabs($VEEVA_Employee_ID) {
        $this->db->select('*');
        $this->db->from($this->table_name . ' Em');
        $this->db->join('Tab_Control tb', 'Em.VEEVA_Employee_ID = tb.VEEVA_Employee_ID');
        $this->db->where(array('Em.VEEVA_Employee_ID' => $VEEVA_Employee_ID));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function generateTabs($VEEVA_Employee_ID = 0, $Product_id = 0) {
        $tabs = $this->Tabs($VEEVA_Employee_ID);
        $this->load->model('Doctor_Model');
        $doctorCount = $this->Doctor_Model->CountDoctor($VEEVA_Employee_ID, $this->Individual_Type);
        $profileCount = $this->ProfilingCount($VEEVA_Employee_ID, $this->Product_Id);
        $rxlabel = $this->Product_Id == 1 ? 'Vials' : 'Rx';
        $hospital = $this->Product_Id == 1 ? 'Hospital' : 'Doctor';
        if ($doctorCount["DoctorCount"] > 0) {
            $PROFILE = ($profileCount["profile_count"] / $doctorCount["DoctorCount"]) * 100;
        } else {
            $PROFILE = 0;
        }

        if (isset($tabs['Tab1']) && $tabs['Tab1'] == 1) {
            $Tab1Location = "'" . site_url('User/Profiling') . "'";
        } elseif (isset($tabs['Tab1']) && $tabs['Tab1'] == 0) {
            $Tab1Location = '#';
        } else {
            $Tab1Location = '#';
        }

        if (isset($tabs['Tab2']) && $tabs['Tab2'] == 1) {
            $Tab2Location = "'" . site_url('User/Set_Target') . "'";
        } elseif (isset($tabs['Tab2']) && $tabs['Tab2'] == 0) {
            $Tab2Location = '#';
        } else {
            $Tab2Location = '#';
        }

        if (isset($tabs['Tab3']) && $tabs['Tab3'] == 1) {
            $Tab3Location = "'" . site_url('User/PlanMenu') . "'";
        } elseif (isset($tabs['Tab3']) && $tabs['Tab3'] == 0) {
            $Tab3Location = '#';
        } else {
            $Tab3Location = '#';
        }
        if (isset($tabs['Tab4']) && $tabs['Tab4'] == 1) {
            $Tab4Location = "'" . site_url('User/ActivityReporting') . "'";
        } elseif (isset($tabs['Tab4']) && $tabs['Tab4'] == 0) {
            $Tab4Location = '#';
        } else {
            $Tab4Location = '#';
        }
        if (isset($tabs['Tab5']) && $tabs['Tab5'] == 1) {
            $Tab5Location = "'" . site_url('User/Reporting') . "'";
        } elseif (isset($tabs['Tab5']) && $tabs['Tab5'] == 0) {
            $Tab5Location = '#';
        } else {
            $Tab5Location = '#';
        }
        if ($this->Product_Id == 1) {
            $vials = "Vials";
        } else {
            $vials = "Rx";
        }

        if ($doctorCount["DoctorCount"] > 0) {
            $tab1Calc = ($profileCount["profile_count"] / $doctorCount["DoctorCount"]) * 100;
        } else {
            $tab1Calc = 0;
        }
        if ($this->Product_Id > 0) {
            $data['show4'] = $this->Rx_Target_month2($this->session->userdata('VEEVA_Employee_ID'), $this->Product_Id, $this->nextMonth);
            $data['Planned'] = $this->Planned_Rx_Count();
            $data['Actual'] = $this->Actual_Rx_Count();
        }

        $activity_planned = $this->activity_planned($this->VEEVA_Employee_ID, $this->Product_Id);
        $activity_actual = $this->activity_actual($this->VEEVA_Employee_ID, $this->Product_Id);

        $prio_dr = $this->prio_dr($this->VEEVA_Employee_ID, $this->Product_Id);
        $target = isset($data['show4']['target']) && $data['show4']['Status'] == 'Submitted' ? $data['show4']['target'] : 0;
        $Planned = isset($data['Planned']['Planned_Rx']) ? $data['Planned']['Planned_Rx'] : 0;
        $Actual = isset($data['Actual']['Actual_Rx']) ? $data['Actual']['Actual_Rx'] : 0;

        $HTML = '<div class="col-lg-12 col-md-12 col-xs-12">

                    <div class="panel panel-default" style="border-color: #fff;">
                        <div class="panel-body" style="    height: 117px;  " >
                            <a style="position: absolute;margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = ' . $Tab1Location . '" >' . $hospital . ' Profiling </a>
                            <div class="pull-right">
                            <input type="hidden" id="profile" value="' . $tab1Calc . '">
                                <input class="knob"   readonly="" id="1" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="' . $PROFILE . '">
                                <span style="    margin: -25px 0px 0px 41px;position: absolute;">' . $profileCount["profile_count"] . '/' . $doctorCount["DoctorCount"] . '</span>
                            </div>
                        </div>
                    </div>
                </div>';
        $HTML .='<div class="col-lg-12 col-md-12 col-xs-12">

                    <div class="panel panel-default" style="border-color: #fff;">
                        <div class="panel-body" style="    height: 117px; ">                       
                            <a style="position: absolute;margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = ' . $Tab2Location . ';">
                               No Of New ' . $vials . ' Targeted For ' . date('M') . "&nbsp" . date('Y') . '
                            </a>
                             <div class="pull-right">
                                <span style="font-size: x-large;position: absolute;margin: 25px 0px 0px -62px;" class="pull-right"><b>' . $target . '</b></span>

                         </div>
                        </div>
                    </div>
                </div>';


        $HTML .='<div class="col-lg-12 col-md-12 col-xs-12" >           
                     <div class="panel panel-default" style="border-color: #fff;">
                        <div class="panel-body" style="    height: 117px; ">
                            <a style="position: absolute;margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = ' . $Tab3Location . '">
                                Planning For The Month Of ' . date('M') . "&nbsp" . date('Y') . ' </a>
                        </div>
                    </div>
                </div>';

        $HTML .='<div class="col-lg-12 col-md-12 col-xs-12">

                    <div class="panel panel-default" style="border-color: #fff;">
                        <div class="panel-body" style="    height: 117px;  ">
                            <a style="position: absolute;margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = ' . $Tab5Location . '" >
                                Reporting Of ' . $vials . '
                            </a>
                            <div class="pull-right">
                                <input class="knob" id="5"  readonly="" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="35">

                                <span style="    margin: -25px 0px 0px 41px;position: absolute;">' . $Actual . '/' . $target . '</span>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default" style="border-color: #fff;">
                    <div class="panel-body" style="    height: 117px; ">
                            <a style="position: absolute;margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = ' . $Tab4Location . '" >

                                Reporting For Activities
                            </a>
                            <div class="pull-right">
                                <input class="knob" id="4"  readonly="" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="35">

                                <span style="    margin: -25px 0px 0px 41px;position: absolute;">' . $activity_actual['activity_actual'] . '/' . $activity_planned["activity_planned"] . '</span>

                            </div>
                        </div>
                    </div>
                </div>';
        return $HTML;
    }

    public function ProfilingCount($VEEVA_Employee_ID, $Product_id = 0) {
        $this->db->select('COUNT(pf.`VEEVA_Employee_ID`) AS profile_count,emp.`VEEVA_Employee_ID`');
        $this->db->from('Employee_Master emp');
        $this->db->join('Employee_Doc ed', 'emp.VEEVA_Employee_ID = ed.VEEVA_Employee_ID AND ed.Status=1');
        $this->db->join('Doctor_Master dm', 'ed.VEEVA_Account_ID = dm.Account_ID');
        $this->db->join('Profiling pf', 'emp.VEEVA_Employee_ID = pf.VEEVA_Employee_ID AND dm.Account_Id = pf.Doctor_Id AND pf.Cycle = ' . $this->Cycle);
        $this->db->where(array('pf.Product_id' => $Product_id, 'emp.VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'pf.Status' => 'Submitted'));
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    public function getActivityDoctor() {
        $this->db->select('dm.*,ap.*');
        $this->db->from('Actual_Doctor_Priority dp');
        $this->db->join('Doctor_Master dm', 'dp.Doctor_Id = dm.Account_ID');
        $this->db->join('Activity_Planning ap', 'ap.Doctor_Id = dm.Account_ID AND ap.month = ' . $this->nextMonth . ' AND ap.Year = "' . $this->nextYear . '"  AND ap.Product_Id = ' . $this->Product_Id, 'left');
        $this->db->join('Activity_Master am','ap.Activity_Id = am.Activity_id  AND am.Status = 0','left');
        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
            $where = "dp.VEEVA_Employee_ID ='$this->VEEVA_Employee_ID' AND dp.Product_id='4' AND dp.month = '$this->nextMonth' AND dm.Individual_Type = '$this->Individual_Type'  OR dp.VEEVA_Employee_ID ='$this->VEEVA_Employee_ID' AND dp.Product_id='6' AND dp.month = '$this->nextMonth' AND dm.Individual_Type = '$this->Individual_Type' ";
            $this->db->where($where);
        } else {
            $this->db->where(array('dp.Product_Id' => $this->Product_Id, 'dp.VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'dp.month' => $this->nextMonth, 'dm.Individual_Type' => $this->Individual_Type));
        }
        $this->db->group_by('dp.Doctor_Id');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function getActivityDoctor2($id, $product_id) {
        if ($product_id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }

        $this->db->select('dm.*,ap.*');
        $this->db->from('Doctor_Master dm');
        $this->db->join('Activity_Planning ap', 'ap.Doctor_Id = dm.Account_ID AND ap.month = ' . $this->nextMonth . ' AND Year = ' . $this->nextYear . ' AND ap.Product_Id = ' . $this->Product_Id, 'left');
        $this->db->join('Activity_Master am','ap.Activity_Id = am.Activity_id  AND am.Status = 0');
        $where = "ap.VEEVA_Employee_ID ='$id'  AND dm.Individual_Type = '$Individual_Type'  ";
        $this->db->where($where);
        $this->db->group_by('ap.Doctor_Id');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function getPlannedActivityDoctor($month = "") {
        $this->db->select('dm.Account_ID,dm.Account_Name,dm.Individual_Type, `ap`.Activity_Id,`ap`.VEEVA_Employee_ID,ap.Act_Plan,`ap`.Doctor_Id,ap.Product_Id,rp.`Activity_Done`,rp.`Activity_Detail`,rp.`Reason`,rp.Approve_Status,rp.Status');
        $this->db->from('(SELECT * FROM Activity_Planning WHERE VEEVA_Employee_ID = "' . $this->VEEVA_Employee_ID . '" AND month = ' . $month . ' AND Year ="' . $this->nextYear . '" AND Product_Id =' . $this->Product_Id . ' AND Approve_Status = "Approved" ) AS ap');
        $this->db->join('Doctor_Master dm', 'ap.Doctor_Id = dm.Account_ID');
        $this->db->join('Activity_Reporting rp', 'rp.Doctor_Id = dm.Account_ID AND rp.Product_Id = ' . $this->Product_Id . ' AND rp.VEEVA_Employee_ID = "' . $this->VEEVA_Employee_ID . '" AND rp.month =' . $month . '  AND rp.Year =' . $this->nextYear . '  ', 'LEFT');
        $this->db->group_by('ap.Doctor_Id');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function getPlannedActivityDoctor2($id, $Product_Id, $month) {
        if ($Product_Id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $this->db->select('dm.*,ap.*');
        $this->db->from('Doctor_Master dm', 'dp.Doctor_Id = dm.Account_ID');
        $this->db->join('Activity_Reporting ap', 'ap.Doctor_Id = dm.Account_ID AND ap.month = ' . $month . ' AND Year = ' . $this->nextYear . ' AND ap.Product_Id = ' . $this->Product_Id, 'left');
        $where = "ap.VEEVA_Employee_ID ='$id' AND dm.Individual_Type = '$Individual_Type' ";
        $this->db->where($where);
        $this->db->group_by('ap.Doctor_Id');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    function getPlanning($VEEVA_Employee_ID, $Product_id = 0, $month = 0, $Year = '2016', $where = 'false', $doctor_ids = array()) {
        $this->db->select('rxp.*,dm.*,pf.Winability,pf.Patient_Rxbed_In_Month,pf.Patient_Seen_month');
        $this->db->from('Employee_Doc ed');
        $this->db->join('Doctor_Master dm', 'dm.Account_ID = ed.VEEVA_Account_ID');
        $this->db->join('Profiling pf', 'dm.Account_ID = pf.Doctor_Id AND pf.Product_Id = ' . $Product_id . ' AND pf.VEEVA_Employee_ID = "' . $VEEVA_Employee_ID . '" AND pf.Cycle = ' . $this->Cycle, 'LEFT');
        $this->db->join('Rx_Planning rxp', 'dm.Account_ID = rxp.Doctor_Id AND rxp.Product_Id = ' . $Product_id . ' AND rxp.Year = "' . $Year . '" AND rxp.month = "' . $month . '" AND rxp.VEEVA_Employee_ID = "' . $VEEVA_Employee_ID . '"', 'LEFT');

        //$where = "ed.VEEVA_Employee_ID ='$VEEVA_Employee_ID' AND dm.Individual_Type = '$this->Individual_Type' ";
        $this->db->where(array('ed.VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'dm.Individual_Type' => $this->Individual_Type, 'ed.Status' => '1'));
        $this->db->group_by('dm.Account_ID');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    function getReporting($VEEVA_Employee_ID, $Product_id = 0, $month = 0, $Year = '2016', $date, $where = 'false', $doctor_ids = array()) {
        $date = date('Y-m-d', strtotime($date));
        $sql = "SELECT 
                    `rxp`.`Planned_Rx`,
                    `dm`.*,
                    Actual_Rx2,
                    Actual_Rx,
                    `pf`.`Winability`,
                    `pf`.`Patient_Rxbed_In_Month`,
                    `pf`.`Patient_Seen_month`,                   
                    act.Approve_Status
                  FROM
                    (SELECT * FROM `Employee_Doc` WHERE VEEVA_Employee_ID = '$VEEVA_Employee_ID' ) as ed 
                    JOIN `Doctor_Master` dm 
                      ON `dm`.`Account_ID` = `ed`.`VEEVA_Account_ID` 
                    LEFT JOIN `Profiling` pf                     
                      ON `dm`.`Account_ID` = `pf`.`Doctor_Id` AND pf.Product_Id = {$Product_id}"
                . " AND pf.Cycle = {$this->Cycle} AND pf.VEEVA_Employee_ID = '$VEEVA_Employee_ID' 
                    LEFT JOIN `Rx_Planning` rxp 
                      ON `dm`.`Account_ID` = `rxp`.`Doctor_Id` 
                      AND rxp.Product_Id = {$Product_id} 
                      AND rxp.Year = '$Year' 
                      AND rxp.month = '$month' 
                      AND rxp.VEEVA_Employee_ID = '$VEEVA_Employee_ID' 
                    LEFT JOIN (
                        SELECT SUM(CASE WHEN DATE_FORMAT(created_at,'%Y-%m-%d') = '$date' THEN Actual_Rx END ) AS Actual_Rx2,
                                SUM(Actual_Rx) AS Actual_Rx,(CASE
                                    WHEN DATE_FORMAT(created_at, '%Y-%m-%d') = '$date' 
                                    THEN Approve_Status 
                                    ELSE Approve_Status
                                  END ) AS Approve_Status,Doctor_Id,VEEVA_Employee_ID,created_at FROM Rx_Actual WHERE Product_Id = {$Product_id} 
                                    AND Year = '$Year' 
                                    AND month =  '$month' 
                                    AND VEEVA_Employee_ID = '$VEEVA_Employee_ID' GROUP BY Doctor_Id
                    ) as act 
                      ON `dm`.`Account_ID` = `act`.`Doctor_Id` 
                      AND act.VEEVA_Employee_ID = '$VEEVA_Employee_ID' 
                      
                  WHERE `ed`.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                    AND `dm`.`Individual_Type` = '$this->Individual_Type' AND `ed`.`Status`='1' 
                  GROUP BY `dm`.`Account_ID`,ed.VEEVA_Employee_ID ORDER BY act.created_at DESC ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function getPlanningAproval($VEEVA_Employee_ID, $Product_id = 0, $month = 0, $Year = '2016', $where = 'false', $doctor_ids = array()) {
        if ($Product_id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $this->db->select('rxp.*,dm.*');
        $this->db->from('Employee_Doc ed');
        $this->db->join('Doctor_Master dm', 'dm.Account_ID = ed.VEEVA_Account_ID', 'INNER');
        $this->db->join('Rx_Planning rxp', 'dm.Account_ID = rxp.Doctor_Id AND rxp.Product_Id = ' . $Product_id . ' AND rxp.Year = "' . $Year . '" AND rxp.month = "' . $month . '" AND rxp.VEEVA_Employee_ID = "' . $VEEVA_Employee_ID . '"', 'INNER');
        $where = "ed.VEEVA_Employee_ID ='$VEEVA_Employee_ID' AND dm.Individual_Type = '$Individual_Type'  AND `ed`.`Status`='1' ";
        $this->db->where($where);
        $this->db->group_by('dm.Account_ID');
        $this->db->order_by('rxp.Planned_Rx DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    function getPlanning2($VEEVA_Employee_ID, $Product_id = 0, $month = 0, $Year = '2016', $where = 'false', $doctor_ids = array()) {

        $doctor_id = join(",", $doctor_ids);
        $sql = "SELECT rxp.*,dm.*,pf.Winability,pf.Patient_Rxbed_In_Month,pf.Patient_Seen_month FROM Employee_Master emp "
                . " INNER JOIN Employee_Doc ed ON ed.VEEVA_Employee_ID = emp.VEEVA_Employee_ID   AND `ed`.`Status`='1'"
                . " INNER JOIN Doctor_Master dm ON dm.Account_ID = ed.VEEVA_Account_ID "
                . " LEFT JOIN Profiling pf ON dm.Account_ID = pf.Doctor_ID AND pf.Cycle = {$this->Cycle}"
                . " LEFT JOIN Rx_Planning rxp ON dm.Account_ID = rxp.Doctor_Id "
                . " WHERE rxp.Doctor_Id IN (" . $doctor_id . ") AND rxp.Product_id = '$Product_id' AND emp.VEEVA_Employee_ID = '$VEEVA_Employee_ID' AND rxp.month = '$month' AND rxp.Year = '$Year'   "
                . " GROUP BY dm.Account_ID order by FIELD(rxp.Doctor_Id ," . $doctor_id . ")";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function generatePlanningTab($type = 'Planning', $priority = 'false', $doctor_ids = array()) {
        $result1 = $this->Rx_Target_month($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth, $this->nextYear);

        if (isset($result1->target) && $result1->target > 0) {
            if ($priority == 'true') {
                $result = $this->User_model->getPlanning2($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth, $this->nextYear, 'true', $doctor_ids);
            } else {
                $result = $this->User_model->getPlanning($this->VEEVA_Employee_ID, $this->Product_Id, $this->nextMonth, $this->nextYear);
                //var_dump($result);
            }
            if (empty($result)) {
                $this->load->model('Doctor_Model');
                $result = $this->Doctor_Model->getDoctor($this->VEEVA_Employee_ID, $this->Individual_Type);
                //var_dump($result);
            }
            if ($type == 'Planning') {
                $html = form_open('User/doctorList');
            } elseif ($type == 'Actual') {
                $html = form_open('User/Prescription_Doctor_List');
            }


            if ($this->Product_Id == 1) {
                $vials = "Vials";
                $hospital = "Hospital";
            } else {
                $vials = "Rx";
                $hospital = "Doctor";
            }

            $html .= '<table class="table table-bordered" id="datatable">
                <thead>
                <tr>
                    <th>' . $hospital . ' List</th>';
            if ($type == 'Planning') {
                $html .= '<th>Winability</th><th>Dependency</th>';
                if ($this->Product_Id == 1) {
                    $html .= '<th>LYSIS Share</th>';
                } else {
                    $html .= '<th>BI Market Share</th>';
                }
            }

            $html .= '<th>' . date('M', strtotime('-3 month')) . $vials . ' </th>
                            <th>' . date('M', strtotime('-2 month')) . $vials . '</th>
                            <th>' . date('M', strtotime('-1 month')) . $vials . '</th>
                            <th>New ' . $vials . ' Targeted For ' . date('M', strtotime($this->nextMonth)) . ' </th>';
            if ($type == 'Planning') {
                $html .= '<th>New ' . $vials . ' Targeted For ' . date('M', strtotime($this->nextMonth)) . ' </th></tr></thead><tbody>';
            } elseif ($type == 'Actual') {
                $html .= '<th>Cumulative Month to Date</th><th>Actual</th></tr></thead><tbody>';
            } else {
                $html .= '</tr></thead><tbody>';
            }


            $month = date('n', strtotime('-1 month'));
            $lastMonthRx = $this->countLastMonthRx($month);
            $currentMonthRx = $this->countPlannedRx(date('n'));
            if (isset($result) && !empty($result)) {
                foreach ($result as $doctor) {
                    $planned_rx = isset($doctor->Planned_Rx) ? $doctor->Planned_Rx : "";
                    $actual_rx = isset($doctor->Actual_Rx) ? $doctor->Actual_Rx : "";


                    $month1 = date('n', strtotime('-3 month'));
                    $month2 = date('n', strtotime('-2 month'));
                    $month3 = date('n', strtotime('-1 month'));
                    $month4 = date('n');
                    $year1 = date('Y', strtotime('-3 month'));
                    $year2 = date('Y', strtotime('-2 month'));
                    $year3 = date('Y', strtotime('-1 month'));
                    $year4 = date('Y');

                    $month1Actual = 0;
                    $month2Actual = 0;
                    $month3Actual = 0;
                    $month4Actual = 0;

                    $last3MonthRx = $this->Last3MonthsRx($month1, $month2, $month3, $month4, $year1, $year2, $year3, $year4, $doctor->Account_ID);
                    if (!empty($last3MonthRx)) {
                        $count = 1;
                        foreach ($last3MonthRx as $value) {
                            if ($value->month === $month1) {
                                $month1Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : '';
                            } elseif ($value->month === $month2) {
                                $month2Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : '';
                            } elseif ($value->month === $month3) {
                                $month3Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : '';
                            } elseif ($value->month === $month4) {
                                $month4Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : '';
                            }
                        }
                    }
                    $winability = isset($doctor->Winability) ? $doctor->Winability : '';
                    $month4rx = $month4Actual;
                    if ($lastMonthRx->Actual_Rx > 0) {
                        $dependancy = round(($month3Actual / $lastMonthRx->Actual_Rx ) * 100, 0, PHP_ROUND_HALF_EVEN);
                    } else {
                        $dependancy = 0;
                    }
                    if ($this->Product_Id == 1) {
                        if (isset($doctor->Patient_Seen_month) && $doctor->Patient_Seen_month > 0) {
                            $BI_Share = round(($month3Actual / $doctor->Patient_Seen_month) * 100, 0, PHP_ROUND_HALF_EVEN);
                        } else {
                            $BI_Share = '';
                        }
                    } else {
                        if (isset($doctor->Patient_Rxbed_In_Month) && $doctor->Patient_Rxbed_In_Month > 0) {
                            $BI_Share = round(($month3Actual / $doctor->Patient_Rxbed_In_Month) * 100, 0, PHP_ROUND_HALF_EVEN);
                        } else {
                            $BI_Share = '';
                        }
                    }


                    if ($priority == 'true') {
                        $result = $this->User_model->ActualPriorityExist($doctor->Account_ID);
                        if (!empty($result)) {
                            $html .= '<tr>
                        <td><input type = "checkbox" name = "priority[]" checked="checked" value = "' . $doctor->Account_ID . '" >   ' . $doctor->Account_Name . '';
                        } else {
                            $html .= '<tr>
                        <td><input type = "checkbox" name = "priority[]" value = "' . $doctor->Account_ID . '" >   ' . $doctor->Account_Name . '';
                        }
                    } else {
                        $html .= '<tr>
                        <td>' . $doctor->Account_Name . '';
                    }


                    $html .='<p>Speciality : ' . $doctor->Specialty . '</p></a></td>';
                    if ($type == 'Planning') {
                        $html .= '<td>' . $winability . '</td><td>' . $dependancy . '%</td>
                                   <td>' . $BI_Share . '</td>';
                    }

                    $html .='<td>' . $month1Actual . '</td>
                            <td>' . $month2Actual . '</td>
                            <td>' . $month3Actual . '</td>';
                    if ($type == 'Planning') {
                        if ($priority == 'true') {
                            $html .= '<td>' . $planned_rx . '</td><td> <input name = "value[]" min="0" disabled="disabled" class = "val" type = "number" value = "' . $planned_rx . '"/><input type = "hidden" name = "doc_id[]" value = "' . $doctor->Account_ID . '"/></td></tr>';

                            /* if (!empty($result)) {
                              $html .='<td><a onclick="deleteEmp(\'' . site_url('User/DeletePriority?id=') . $doctor->Account_ID . '\')" >Delete</a></td></tr>';
                              } else {
                              $html .= '<td></td></tr>';
                              } */
                        } else {
                            $html .= '<td>' . $planned_rx . '</td><td> <input name = "value[]" min="0" class = "val" type = "number" value = "' . $planned_rx . '"/><input type = "hidden" name = "doc_id[]" value = "' . $doctor->Account_ID . '"/></td>
                                </tr>';
                        }
                    } elseif ($type == 'Actual') {
                        $html .= '<td>' . $planned_rx . '<input type = "hidden" name = "doc_id[]" value = "' . $doctor->Account_ID . '"/></td>
                                <td>' . $month4rx . '</td>
                                <td> <input name = "value[]" type = "number" class="val" min="0" value = ""/></td>
                                </tr>';
                    }
                }
            }
            $html.='</tbody></table>';
        } else {
            $html = "<h1>Please Set Target Before Planning</h1>";
        }

        return $html;
    }

    function getMonthwiseRx($Doctor_Id = 0, $month = 0, $Year = '2015') {
        $this->db->select('*');
        $this->db->from('Rx_Actual');
        $this->db->where(array('Doctor_id' => $Doctor_Id, 'Product_id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'month' => $month, 'Year' => $Year));
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row();
    }

    function Last3MonthsRx($month1, $month2, $month3, $month4, $year1, $year2, $year3, $year4, $Doctor_ID) {
        $sql = "SELECT
                SUM(Actual_Rx) AS Actual_Rx,month

               FROM (`Rx_Actual`)
               WHERE 
               `Doctor_id` =  '$Doctor_ID'
               AND `Product_id` =  '$this->Product_Id'
               AND `VEEVA_Employee_ID` =  '$this->VEEVA_Employee_ID'
               AND `month` =  '$month1'
               AND `Year` =  '$year1'

               OR `month` =  '$month2'
               AND `Doctor_id` =  '$Doctor_ID'
               AND `Product_id` =  '$this->Product_Id'
               AND `VEEVA_Employee_ID` =  '$this->VEEVA_Employee_ID'
               AND `Year` =  '$year2'

               OR `month` =  '$month3'
               AND `Doctor_id` =  '$Doctor_ID'
               AND `Product_id` =  '$this->Product_Id'
               AND `VEEVA_Employee_ID` =  '$this->VEEVA_Employee_ID'
               AND `Year` =  '$year3'
                   
                OR `month` =  '$month4'
               AND `Doctor_id` =  '$Doctor_ID'
               AND `Product_id` =  '$this->Product_Id'
               AND `VEEVA_Employee_ID` =  '$this->VEEVA_Employee_ID'
               AND `Year` =  '$year4' GROUP BY month   ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function countLastMonthRx($month = 0, $Year = '2015') {
        $this->db->select('SUM(Actual_Rx) AS Actual_Rx');
        $this->db->from('Rx_Actual rx');
        $this->db->join('Doctor_Master dm', 'rx.Doctor_Id = dm.Account_ID');
        $this->db->where(array('rx.Product_id' => $this->Product_Id, 'rx.VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'rx.month' => $month, 'rx.Year' => $Year));
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row();
    }

    function countPlannedRx($month = 0) {
        $this->db->select('SUM(Actual_Rx) AS Planned_Rx');
        $this->db->from('Rx_Actual');
        $this->db->where(array('Product_id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'month' => $month));
        $query = $this->db->get();
        return $query->row();
    }

    function getWinability($Doctor_Id = 0) {
        $this->db->select('*');
        $this->db->from('Profiling');
        $this->db->where(array('Doctor_id' => $Doctor_Id, 'Product_id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Cycle' => $this->Cycle));
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    function calcWinability($Win_Q1, $Win_Q2, $Win_Q3) {

        $winabilty = '';

        if ($this->Product_Id == 1) {
            if ($Win_Q1 == 'No') {
                $winabilty = '<a class = "control-item badge badge-negative">L</a>';
            } elseif ($Win_Q1 == 'Yes') {
                if ($Win_Q2 == 'No') {
                    $winabilty = '<a class = "control-item badge badge-primary">M</a>';
                } elseif ($Win_Q2 == 'Yes' && $Win_Q3 == 'No') {
                    $winabilty = '<a class = "control-item badge badge-primary">M</a>';
                } elseif ($Win_Q2 == 'Yes' && $Win_Q3 == 'Yes') {
                    $winabilty = '<a class = "control-item badge badge-positive">H</a>';
                }
            }
        } elseif ($this->Product_Id == 2 || $this->Product_Id == 3 || $this->Product_Id == 4 || $this->Product_Id == 5 || $this->Product_Id == 6) {
            if ($Win_Q1 == 'Yes' && $Win_Q2 == 'Yes' && $Win_Q3 == 'No') {
                $winabilty = '<a class = "control-item badge badge-positive">H</a>';
            } elseif ($Win_Q1 == 'No' && $Win_Q2 == 'Yes' && $Win_Q3 == 'No' || $Win_Q1 == 'Yes' && $Win_Q2 == 'No' && $Win_Q3 == 'No' || $Win_Q1 == 'Yes' && $Win_Q2 == 'No' && $Win_Q3 == 'Yes' || $Win_Q1 == 'Yes' && $Win_Q2 == 'Yes' && $Win_Q3 == 'Yes') {
                $winabilty = '<a class = "control-item badge badge-primary">M</a>';
            } elseif ($Win_Q1 == 'No' && $Win_Q2 == 'No' && $Win_Q3 == 'No' || $Win_Q1 == 'No' && $Win_Q2 == 'No' && $Win_Q3 == 'Yes' || $Win_Q1 == 'No' && $Win_Q2 == 'Yes' && $Win_Q3 == 'Yes') {
                $winabilty = '<a class = "control-item badge badge-negative">L</a>';
            }
        }

        return $winabilty;
    }

    function PriorityIds() {
        $doctors = array();
        $sql = "SELECT `Doctor_Id` FROM `Doctor_Priority` WHERE `Delta` >= 20
        AND VEEVA_Employee_Id = '$this->VEEVA_Employee_ID' and Product_Id = '$this->Product_Id'  AND month = '$this->nextMonth' ORDER BY Delta DESC ";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($doctors, $value->Doctor_Id);
            }
        }

        $sql = "SELECT `Doctor_Id` FROM `Doctor_Priority` WHERE `Dependancy` >= 20
                AND VEEVA_Employee_Id = '$this->VEEVA_Employee_ID' and Product_Id = '$this->Product_Id' AND month = '$this->nextMonth' ORDER BY Dependancy DESC  ";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($doctors, $value->Doctor_Id);
            }
        }
        $doctors = array_unique($doctors);

        $sql = "SELECT `Doctor_Id` FROM `Doctor_Priority` 
                WHERE VEEVA_Employee_Id = '$this->VEEVA_Employee_ID' and Product_Id = '$this->Product_Id' AND month = '$this->nextMonth' ORDER BY `Planned_Rx` DESC                ";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($doctors, $value->Doctor_Id);
            }
        }

        $doctors = array_unique($doctors);
        $doctors2 = array();
        if (!empty($doctors)) {
            foreach ($doctors as $value) {
                array_push($doctors2, "'" . $value . "'");
            }
        }

        //$doctors = array_unique($doctors);
        return $doctors2;
    }

    function getActivityList() {
        $this->db->select('*');
        $this->db->from('Activity_Master');
        $this->db->where(array('Product_id' => $this->Product_Id, 'Status' => 0));
        $query = $this->db->get();
        return $query->result();
    }

    function Planned_Rx_Count() {
        $this->db->select('SUM(`Planned_Rx`) AS Planned_Rx');
        $this->db->from('Rx_Planning');
        $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'month' => $this->nextMonth, 'Year' => $this->nextYear, 'Planning_Status' => 'Submitted'));
        $query = $this->db->get();
        return $query->row_array();
    }

    function Actual_Rx_Count() {
        $this->db->select('SUM(`Actual_Rx`) AS Actual_Rx');
        $this->db->from('Rx_Actual');
        $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'month' => $this->nextMonth, 'Year' => $this->nextYear, 'Approve_Status' => 'Approved'));
        $query = $this->db->get();
        return $query->row_array();
    }

    function getPlannedActivityList($Doctor_Id) {
        $this->db->select('*');
        $this->db->from('Activity_Planning ap');
        $this->db->join('Activity_Master am', 'ap.Activity_Id = am.Activity_id AND am.Status = 0 ');
        $this->db->where(array('ap.Product_id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID));
        $query = $this->db->get();
        return $query->result();
    }

    function generateCheckboxList($result, $id) {
        $html = '';
        if (!empty($result)) {
            foreach ($result as $item) {
                $html .= '<p><input name = "activity[]" value = "' . $item->Activity_id . '" type = "checkbox" /> ' . $item->Activity_Name . '</p>';
            }
        }
        return $html;
    }

    function PlanningExist($Doctor_Id = "") {
        $this->db->select('*');
        $this->db->from('Rx_Planning');
        $this->db->where(array('Product_Id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Doctor_Id' => $Doctor_Id, 'month' => $this->nextMonth, 'Year' => $this->nextYear));
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row();
    }

    function password($id, $data) {
        if ($id != '') {
            $this->db->where(array('VEEVA_Employee_ID' => $id));
            return $this->db->update('Employee_Master', $data);
        }
    }

    function password_status($id) {
        $this->db->select('*');
        $this->db->from('Employee_Master');
        $this->db->where(array('VEEVA_Employee_ID' => $id));
        $query = $this->db->get();
        return $query->row_array();
    }

    function employee_id($id) {
        $this->db->select('*');
        $this->db->from('Employee_Master');
        $this->db->where(array('Username' => $id));
        $query = $this->db->get();
        return $query->row_array();
    }

    function password_count($id) {
        $current_date = date('Y-m-d H:i:s');
        $previous_date = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $this->db->select('COUNT(VEEVA_Employee_ID) AS cnt');
        $this->db->from('password_count');
        $where = "VEEVA_Employee_ID = '$id' AND created_at BETWEEN '$previous_date' AND '$current_date' ";
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function password_save($data) {
        return $this->db->insert('password_count', $data);
    }

    function update_status($id, $data) {
        $this->db->where(array('Username' => $id));
        return $this->db->update('Employee_Master', $data);
    }

    function update_last_login($id, $data) {
        $this->db->where(array('VEEVA_Employee_ID' => $id));
        return $this->db->update('Employee_Master', $data);
    }

    function PriorityExist($Doctor_Id) {
        $this->db->select('*');
        $this->db->from('Doctor_Priority');
        $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'Doctor_Id' => $Doctor_Id));
        $query = $this->db->get();
        return $query->row_array();
    }

    function ActualPriorityExist($Doctor_Id) {
        $this->db->select('*');
        $this->db->from('Actual_Doctor_Priority');
        $this->db->where(array('VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Product_Id' => $this->Product_Id, 'Doctor_Id' => $Doctor_Id, 'month' => $this->nextMonth, 'Year' => $this->nextYear));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function product_detail($VEEVA_Employee_ID, $Product_id, $month, $year) {
        $this->db->select('SUM(Actual_Rx) AS Actual_Rx');
        $this->db->from('Rx_Actual rx');
        $this->db->join('Doctor_Master dm', 'rx.Doctor_Id = dm.Account_ID');
        $this->db->where(array('rx.VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'rx.Product_id' => $Product_id, 'rx.month' => $month, 'Year' => $year, 'rx.Status' => 'Submitted'));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function kpi($VEEVA_Employee_ID, $Product_id, $month, $year) {
        $sql = "SELECT 
                SUM(`Planned_Rx`) AS planned_rx 
                FROM
                ( SELECT * FROM `Rx_Planning` rp WHERE 
                `month`='$month'
                AND `VEEVA_Employee_ID` = '$VEEVA_Employee_ID'
                AND `Planning_Status` = 'Submitted' 
                AND `Product_id` = '$Product_id' 
                AND `Year` = '$year') AS rp
                   INNER JOIN    Employee_Doc ed 
                  ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
                  INNER JOIN Doctor_Master Dm 
                  ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function product_detail_user($VEEVA_Employee_ID, $Product_id, $month, $year) {
        $this->db->select('COUNT(DISTINCT(`Doctor_Id`)) AS doctor_count');
        $this->db->from('`Rx_Actual`');
        $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'Product_id' => $Product_id, 'month' => $month, 'Year' => $year));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function activity_planned($VEEVA_Employee_ID, $Product_id) {
        $sql = "SELECT 
            COUNT(rp.`Activity_Id`) AS activity_planned 
          FROM
           ( SELECT * FROM`Activity_Planning` 
                WHERE `month` = $this->nextMonth 
                AND `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                AND `Status` = 'Submitted'
                AND `Product_id` = '$Product_id'
                AND `Year` = '$this->nextYear' )AS rp

            INNER JOIN    Employee_Doc ed 
              ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
              INNER JOIN Doctor_Master Dm 
              ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID
               ";
        $query = $this->db->query($sql);
        //echo $sql.'<br>';
        return $query->row_array();
    }

    public function activity_actual($VEEVA_Employee_ID, $Product_id) {
        $sql = "SELECT 
        COUNT(rp.Act_Plan) AS activity_actual 
        FROM
          (SELECT * FROM `Activity_Reporting` 
        WHERE `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
          AND `Product_id` = '$Product_id' 
          AND `Year` = '$this->nextYear' 
          AND `month` = '$this->nextMonth' 
          AND `Status` = 'Submitted' 
          AND `Activity_Done` = 'Yes' ) AS rp

          INNER JOIN    Employee_Doc ed 
            ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
            INNER JOIN Doctor_Master Dm 
            ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID
             ";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function prio_dr($VEEVA_Employee_ID, $Product_id) {
        $this->db->select('COUNT(DISTINCT(`Doctor_Id`)) AS doctor_id');
        $this->db->from('`Actual_Doctor_Priority`');
        if ($this->Product_Id == 4 || $this->Product_Id == 6) {
            $where = "VEEVA_Employee_ID ='$VEEVA_Employee_ID' AND Product_id='4' AND Year='$this->nextYear' AND month='$this->nextMonth' OR VEEVA_Employee_ID ='$VEEVA_Employee_ID' AND Product_id='6' AND Year='$this->nextYear' AND month='$this->nextMonth' ";
            $this->db->where($where);
        } else {
            $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'Product_id' => $Product_id, 'Year' => $this->nextYear, 'month' => $this->nextMonth));
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    public function generateActivityTable($result = array(), $type = "") {
        $HTML = '';
        if ($this->Product_Id == 1) {
            $hospital = "Hospital";
        } else {
            $hospital = "Doctor";
        }
        $Activities = $this->getActivityList();

        if (!empty($result)) {
            $HTML = '<table class="table table-bordered">';
            $HTML .= '<tr>
                                <th>
                                    ' . $hospital . ' Name
                                </th>
                                <th>Activity</th>';
            if ($type == 'Reporting') {
                $HTML .= '<th>Action</th>';
            }
            $HTML .= '</tr>';

            $allApproved = TRUE;
            foreach ($result as $value) {

                if (isset($value->Act_Plan) && !is_null($value->Act_Plan)) {
                    $ActivityList = $this->Master_Model->generateDropdown($Activities, 'Activity_id', 'Activity_Name', $value->Activity_Id);
                } else {
                    $ActivityList = $this->Master_Model->generateDropdown($Activities, 'Activity_id', 'Activity_Name');
                }
                $style = '';
                if (isset($value->Approve_Status) && $value->Approve_Status == 'Approved') {
                    $style = 'style="background-color:#c6ebd9;"';
                } elseif (isset($value->Approve_Status) && $value->Approve_Status == 'Un-Approved') {
                    $style = 'style="background-color: #ff9999;"';
                    $allApproved = FALSE;
                } else {
                    $allApproved = FALSE;
                }
                $HTML .= '<tr ' . $style . ' ><td>' . $value->Account_Name . '<input type="hidden" name="Doctor_Id[]" value="' . $value->Account_ID . '" ></td>';

                if ($type == 'Reporting') {
                    $activity_detail = isset($value->Activity_Detail) ? $value->Activity_Detail : '';
                    $reason = isset($value->Reason) ? $value->Reason : '';
                    $Activity_Done = isset($value->Activity_Done) ? $value->Activity_Done : '';
                    $Status = isset($value->Status) && $value->Status != '' ? $value->Status : '';

                    $HTML .= '<td><input type="hidden" value="' . $value->Activity_Id . '" name="Activity_Id[]" ><select class="form-control" readonly="readonly" disabled="disabled" name="Activity_Id[]"><option value>Select Activity</option>' . $ActivityList . '</select></td>';
                    $HTML .='<td><div class="col-xs-8">
                        <div class="toggle">';

                    if ($Activity_Done == "Yes" && $Status == 'Submitted' || $Activity_Done == "Yes" && $Status == 'Draft') {
                        $HTML .=' <label><input type="radio" checked="checked" name="' . $value->Account_ID . '" value="Yes"><span class="input-checked" id="' . $value->Account_ID . '-1 ">Yes</span>';
                    } else {
                        $HTML .=' <label><input type="radio" name="' . $value->Account_ID . '" value="Yes"><span id="' . $value->Account_ID . '-1 ">Yes</span>';
                    }
                    $HTML .='</label>    
                        </div>
                        <div class="toggle">';
                    if ($Activity_Done == "No" && $Status == 'Submitted' || $Activity_Done == "No" && $Status == 'Draft') {
                        $HTML .=' <label><input type="radio" checked="checked" name="' . $value->Account_ID . '" value="No"><span class="input-checked" id="' . $value->Account_ID . '-2 " >No</span>';
                    } else {
                        $HTML .=' <label><input type="radio" name="' . $value->Account_ID . '" value="No"><span id="' . $value->Account_ID . '-2 " >No</span>';
                    }
                    $HTML .='</label>
                        </div>
                    </div>';

                    if ($Status == 'Submitted') {
                        if ($Activity_Done == 'Yes') {
                            $HTML .= $activity_detail . '<input type="hidden" class="form-control" name="' . $value->Account_ID . 'Activity_Detail"  placeholder="Activity Details" value="' . $activity_detail . '" >';
                        } elseif ($Activity_Done == 'No') {
                            $HTML .=$reason . '<input type="hidden" class="form-control" name="' . $value->Account_ID . 'Reason"  placeholder="Activity Details" value="' . $reason . '" >';
                        }
                    } elseif ($Status == 'Draft') {
                        $HTML .='<div id="heading' . $value->Account_ID . '" class="custom-collapse " style="display: none">
                                <div class="row row-margin-top">
                                    <div class="col-xs-12 col-lg-12"><textarea id="act' . $value->Account_ID . '" class="form-control" name="' . $value->Account_ID . 'Activity_Detail"  placeholder="Activity Details">' . $activity_detail . '</textarea> </div> 
                                </div> 
                            </div><div id="reason' . $value->Account_ID . '" class="custom-collapse " style="display: none">
                                <div class="row row-margin-top">
                                    <div class="col-xs-12 col-lg-12"><textarea id="res' . $value->Account_ID . '" class="form-control" name="' . $value->Account_ID . 'Reason"  placeholder="Reason">' . $reason . '</textarea> </div> 
                                </div> 
                            </div>';
                    } else {
                        $HTML .='<div id="heading' . $value->Account_ID . '" class="custom-collapse " style="display: none">
                                <div class="row row-margin-top">
                                    <div class="col-xs-12 col-lg-12"><textarea id="act' . $value->Account_ID . '" class="form-control" name="' . $value->Account_ID . 'Activity_Detail"  placeholder="Activity Details">' . $activity_detail . '</textarea> </div> 
                                </div> 
                            </div><div id="reason' . $value->Account_ID . '" class="custom-collapse " style="display: none">
                                <div class="row row-margin-top">
                                    <div class="col-xs-12 col-lg-12"><textarea id="res' . $value->Account_ID . '" class="form-control" name="' . $value->Account_ID . 'Reason"  placeholder="Reason">' . $reason . '</textarea> </div> 
                                </div> 
                            </div>';
                    }


                    $HTML .='</td>';
                } else {
                    $HTML .= '<td><div class="form-group"><select class="form-control" name="Activity_Id[]"><option value>Select Activity</option>' . $ActivityList . '</select></div></td>';
                }

                $HTML .= '</tr>';
            }
            $HTML .= '</table>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">Save</button>';
            if ($allApproved == TRUE) {
                $HTML .='<button type="submit" id="Submit" class="btn btn-danger">Submit</button>';
            } else {
                $HTML .='<button type="submit" id="Approve" class="btn btn-info">Save For Approval</button>';
            }
            $HTML .='</div>';
        }

        return $HTML;
    }

    public function generateActivityTable2($result = array(), $type = "") {
        $HTML = '';
        if ($this->Product_Id == 1) {
            $hospital = "Hospital";
        } else {
            $hospital = "Doctor";
        }
        $Activities = $this->getActivityList();

        if (!empty($result)) {
            $HTML = '<div class="table-responsive panel"><table class="table table-bordered">';
            $HTML .= '<tr><th>
                                    ' . $hospital . ' Name
                            </th>
                                <th>Activity</th>
                                ';
            if ($type == 'Reporting') {
                $HTML .= '<th>Activity Done</th>';
            }
            $HTML .= '<th><input type="radio" name="toggle" id="check-all" >Approve</th><th><input type="radio" name="toggle" id="uncheck-all" >Reject</th></tr>';

            foreach ($result as $value) {
                $Status = isset($value->Approve_Status) && $value->Approve_Status == 'Un-Approved' ? 'checked' : '';
                if (isset($value->Act_Plan) && !is_null($value->Act_Plan)) {
                    $ActivityList = $this->Master_Model->generateDropdown($Activities, 'Activity_id', 'Activity_Name', $value->Activity_Id);
                } else {
                    $ActivityList = $this->Master_Model->generateDropdown($Activities, 'Activity_id', 'Activity_Name');
                }

                $isApproved = isset($value->Approve_Status) && $value->Approve_Status == 'Approved' ? 'background-color:#c6ebd9;' : '';

                if ($value->Approve_Status == 'Approved') {
                    $HTML .= '<tr style="' . $isApproved . '"><td>' . $value->Account_Name . '</td>';
                    $HTML .= '<td><select class="form-control" disabled="disabled" ><option value="-1">Select Activity</option>' . $ActivityList . '</select></td>';
                    if ($type == 'Reporting') {
                        $HTML .= '<td>' . $value->Activity_Done . '</td>';
                    }
                    $HTML .= '<td><input type="radio" disabled="disabled" checked="checked"  value="Approved"></td>';
                    $HTML .= '<td><input type="radio" disabled="disabled"  ' . $Status . ' value="Un-Approved"></td>';
                } else {
                    $HTML .= '<tr style="' . $isApproved . '"><td>' . $value->Account_Name . '<input type="hidden" name="Doctor_Id[]" value="' . $value->Account_ID . '"></td>';
                    $HTML .= '<td><select class="form-control" disabled="disabled" name="Activity_Id[]"><option value="-1">Select Activity</option>' . $ActivityList . '</select></td>';
                    if ($type == 'Reporting') {
                        $HTML .= '<td>' . $value->Activity_Done . '</td>';
                    }
                    $HTML .= '<td><input type="radio" class="check-all" ' . $Status . ' name="approve_' . $value->Account_ID . '" value="Approved"></td>';
                    $HTML .= '<td><input type="radio" class="uncheck-all" ' . $Status . ' name="approve_' . $value->Account_ID . '" value="Un-Approved"></td>';
                }
                $HTML .= '</tr>';
            }
            $HTML .= '</table></div>'
                    . ' <button type="button" data-toggle="modal" data-target="#CommentModal" class="btn btn-primary pull-right" >Approve</button>';
        } else {
            $HTML .= '<h1>Data Not Available.</h1>';
        }

        return $HTML;
    }

    function ReportingExist($Doctor_Id = "", $month, $date) {
        $date = date('Y-m-d', strtotime($date));
        $this->db->select('*');
        $this->db->from('Rx_Actual');
        $this->db->where(array('Product_Id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Doctor_Id' => $Doctor_Id, 'month' => $month, 'Year' => $this->nextYear, 'DATE_FORMAT(created_at,"%Y-%m-%d")' => $date));
        $query = $this->db->get();
        ///echo $this->db->last_query() . '<br>';
        return $query->row();
    }

    function ActivityReportingExist($Doctor_Id = "", $month = "") {
        $this->db->select('*');
        $this->db->from('Activity_Reporting');
        $this->db->where(array('Product_Id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Doctor_Id' => $Doctor_Id, 'month' => $month, 'Year' => $this->nextYear));
        $query = $this->db->get();
        return $query->row();
    }

    function ActivityPlanned($Doctor_Id = "") {
        $this->db->select('*');
        $this->db->from('Activity_Planning');
        $this->db->where(array('Product_Id' => $this->Product_Id, 'VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'Doctor_Id' => $Doctor_Id, 'month' => $this->nextMonth, 'Year' => $this->nextYear));
        $query = $this->db->get();
        return $query->row();
    }

    function SaveReporting($data = array()) {
        $this->db->insert('Rx_Actual', $data);
        //echo $this->db->last_query();
        return $this->db->insert_id();
    }

    function Update_mobile($VEEVA_Employee_ID, $data) {
        $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID));
        return $this->db->update($this->table_name, $data);
    }

    function Update_password($VEEVA_Employee_ID, $data) {
        $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID));
        return $this->db->update($this->table_name, $data);
    }

    public function All_data($VEEVA_Employee_ID) {
        $sql = "SELECT em.`Full_Name`,em.`Mobile`,em.`password`,em.`Territory`,em.`DOB`,em.`Date_of_Joining`,(em2.`Reporting_To`) AS ZSM,(em.`Reporting_To`) AS ASM  FROM `Employee_Master`em
                INNER JOIN `Employee_Master`em2
                ON em.`Reporting_VEEVA_ID`= em2.`VEEVA_Employee_ID`
                WHERE em.`VEEVA_Employee_ID`='$VEEVA_Employee_ID'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function check_planning($VEEVA_Employee_ID, $Product_Id, $nextMonth, $nextYear) {
        $sql = "SELECT * FROM `Rx_Planning`
                WHERE `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND `Product_Id`= $Product_Id AND month=$nextMonth AND Year=$nextYear ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function priority_check($VEEVA_Employee_ID, $Product_Id, $nextMonth) {
        $sql = "SELECT * FROM `Actual_Doctor_Priority`
                WHERE `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND `Product_Id`= $Product_Id AND month=$nextMonth AND Year='$this->nextYear' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function Activity_reporting_check($VEEVA_Employee_ID, $Product_Id, $Status, $month = "", $year = "") {
        $sql = "SELECT * FROM `Activity_Planning`
                WHERE `VEEVA_Employee_ID`='$VEEVA_Employee_ID' AND Approve_Status = 'Approved' AND `Product_Id`=$Product_Id AND month={$month} AND year = '$year' ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function bdm_doctor_rx($VEEVA_Employee_ID, $month, $year) {
        $sql = "SELECT (dm.`Account_Name`) AS doctor_name,dm.`Account_ID`,COUNT(ra.`Actual_Rx`) AS Rx_Actual,COUNT(rp.`Planned_Rx`) AS rx_planned FROM `Employee_Master` em
                LEFT JOIN `Employee_Doc` ed
                ON em.`VEEVA_Employee_ID`=ed.`VEEVA_Employee_ID`  AND `ed`.`Status`='1'
                INNER JOIN `Doctor_Master`dm
                ON ed.`VEEVA_Account_ID`=dm.`Account_ID`
                LEFT JOIN Rx_Actual ra
                ON dm.`Account_ID`=ra.`Doctor_Id` AND ra.`month`=$month AND ra.`Year`=$year
                LEFT JOIN `Rx_Planning` rp
                ON dm.`Account_ID`=rp.`Doctor_Id` AND rp.`month`=$month AND rp.`Year`=$year
                WHERE em.`VEEVA_Employee_ID`='$VEEVA_Employee_ID'  
                GROUP BY dm.`Account_ID`";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function ASM_kp1($VEEVA_Employee_ID, $month, $year, $product_id) {
        $sql = "SELECT SUM(ra.`Actual_Rx`) as Actual,SUM(rp.`target`) as Planned FROM `Employee_Master` em 
                LEFT JOIN `Rx_Actual` ra 
                ON em.`VEEVA_Employee_ID`=ra.`VEEVA_Employee_ID` AND ra.`month`=$month AND ra.`Year`=$year AND ra.`Product_Id`=$product_id
                LEFT JOIN `Rx_Target` rp 
                ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND rp.`month`=$month AND rp.`Year`=$year AND rp.`Product_Id`=$product_id
                WHERE `Reporting_VEEVA_ID`='$VEEVA_Employee_ID'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function ASM_division($VEEVA_Employee_ID) {
        $sql = "SELECT em.`Division` as division,em.Local_Employee_ID FROM `Employee_Master` em
                WHERE em.`VEEVA_Employee_ID`='$VEEVA_Employee_ID'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function report($VEEVA_Employee_ID, $month, $year, $product) {
        if ($product == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }

        $sql = "SELECT em.`Full_Name`,em.VEEVA_Employee_ID,COUNT(dm.`Account_ID`) AS No_of_Doctors ,COUNT(p.`Doctor_Id`)AS No_of_Doctors_profiled,rt.`target` AS Target_New_Rxn_for_the_month,SUM(rp.`Planned_Rx`) AS Planned_New_Rxn,COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,COUNT(CASE WHEN ar.`Activity_Done`='Yes' THEN 1 END) AS checkk 
                FROM (
                SELECT 
                  VEEVA_Employee_ID,Full_Name
                FROM
                  `Employee_Master` 
                WHERE Reporting_VEEVA_ID = '$VEEVA_Employee_ID') AS em 
               LEFT JOIN 
                      (SELECT 
                        d.`Account_ID`,
                        ed.`VEEVA_Employee_ID` 
                      FROM
                        Doctor_Master d 
                        INNER JOIN Employee_Doc ed 
                          ON ed.`VEEVA_Account_ID` = d.`Account_ID` AND `ed`.`Status`='1'
                      WHERE `VEEVA_Employee_ID` IN 
                        (SELECT 
                          VEEVA_Employee_ID 
                        FROM
                          Employee_Master 
                      WHERE Reporting_VEEVA_ID = '$VEEVA_Employee_ID') AND d.Individual_Type = '$Individual_Type' ) AS dm 
                ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID 
                LEFT JOIN 
                      (SELECT 
                        `Doctor_Id`,
                        `VEEVA_Employee_ID` 
                      FROM
                        Profiling 
                      WHERE `Product_Id` = $product 
                        AND STATUS = 'Submitted' AND Cycle = {$this->Cycle}
                      GROUP BY `Doctor_Id`,
                        `VEEVA_Employee_ID`) AS p 
                      ON em.VEEVA_Employee_ID = p.VEEVA_Employee_ID 
                      AND dm.`Account_ID` = p.Doctor_Id 
 
                LEFT JOIN Rx_Target rt
                ON em.`VEEVA_Employee_ID`=rt.`VEEVA_Employee_ID`AND rt.`Status`='Submitted' AND rt.`Product_Id`=$product AND rt.`Month`=$month AND rt.`Year`=$year
                LEFT JOIN Rx_Planning rp
                ON dm.`Account_ID` = rp.`Doctor_Id`AND rp.`Product_Id`=$product AND rp.`Month`=$month AND rp.`Year`=$year AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID
                LEFT JOIN Activity_Planning ap
                ON dm.`Account_ID` = ap.`Doctor_Id` AND ap.`Product_Id`=$product AND ap.`Month`=$month AND ap.`Year`=$year AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                LEFT JOIN Activity_Reporting ar
                ON dm.`Account_ID` = ar.`Doctor_Id` AND ar.`Product_Id`=$product AND ar.`Month`=$month AND ar.`Year`=$year AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` 
                GROUP BY em.`VEEVA_Employee_ID`";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function getReporting2($VEEVA_Employee_ID, $Product_id = 0, $month = 0, $Year = '2016', $where = 'false', $doctor_ids = array()) {

        if ($Product_id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }


        $sql = "SELECT 
                  `dm`.*,
                  GROUP_CONCAT(`act`.`Rxplan_id`) AS Rxplan_id,
                  SUM(act.Actual_Rx) AS Actual_Rx 
                FROM
                  (`Employee_Doc` ed) 
                  JOIN `Doctor_Master` dm 
                    ON `dm`.`Account_ID` = `ed`.`VEEVA_Account_ID` AND dm.Individual_Type = '$Individual_Type' 
                  INNER JOIN `Rx_Actual` act 
                    ON `dm`.`Account_ID` = `act`.`Doctor_Id` 
                    AND act.Product_Id = {$Product_id} 
                    AND act.Year = '$Year' 
                    AND act.month = '$month'
                    AND act.VEEVA_Employee_ID = '$VEEVA_Employee_ID' 
                WHERE `ed`.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID'  AND `ed`.`Status`='1' 
                  
                GROUP BY `dm`.`Account_ID` ORDER BY Actual_Rx DESC ";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    public function check_email($id) {
        $this->db->select('*');
        $this->db->from('Employee_Master');
        $this->db->where(array('VEEVA_Employee_ID' => $id));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function Reset_pass($id, $data) {
        $this->db->where(array('VEEV_Employee_ID' => $id));
        $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function lastFailedAttempt($VEEVA_Employee_ID) {
        $sql = "SELECT * FROM password_count WHERE VEEVA_Employee_ID = '$VEEVA_Employee_ID' ORDER BY created_at DESC LIMIT 1 ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function check_history($id, $pass) {
        $sql = "select * from Password_History where VEEVA_Employee_ID='$id' AND password='$pass'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function insert_pass($data) {
        return $this->db->insert('Password_History', $data);
    }

    public function getComment($VEEVA_Employee_ID, $Comment_Type, $Product_Id) {
        $this->db->select('*');
        $this->db->from('Asm_Comment');
        $this->db->where(array('VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'Comment_Type' => $Comment_Type, 'Product_Id' => $Product_Id));
        $this->db->order_by('Com_id DESC LIMIT 1');
        $query = $this->db->get();
        return $query->row();
    }

    public function getTerritory($Territory_id) {
        $this->db->select('*');
        $this->db->from('Territory_master');
        $this->db->where(array('id' => $Territory_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function ASM_comment($VEEVA_Employee_ID, $PRODUCT_ID) {
        $sql = "SELECT * FROM Asm_Comment
                WHERE
                `VEEVA_Employee_ID`='$VEEVA_Employee_ID' and `Product_Id`='$PRODUCT_ID' and `Comment_type`='Planning'  AND Comment != '' or  `VEEVA_Employee_ID`='$VEEVA_Employee_ID' and `Product_Id`='$PRODUCT_ID' and `Comment_type`='Activity_Planning' AND Comment != '' ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    public function ASM_comment_rep($VEEVA_Employee_ID, $PRODUCT_ID) {
        $sql = "SELECT * FROM Asm_Comment
                WHERE
                `VEEVA_Employee_ID`='$VEEVA_Employee_ID' and `Product_Id`='$PRODUCT_ID' and `Comment_type`='Reporting' AND Comment != '' or `VEEVA_Employee_ID`='$VEEVA_Employee_ID' and `Product_Id`='$PRODUCT_ID' AND `Comment_type`='Activity_Reporting' AND Comment != '' ";
        $query = $this->db->query($sql);

        return $query->result();
    }

    function Zsmreport($VEEVA_Employee_ID, $month, $year, $product) {
        $month2 = date('n');
        $month1 = date('n') - 1;

        if ($product == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $sql = "SELECT 
                    em.Reporting_To AS Full_Name,
                    em.Reporting_VEEVA_ID AS VEEVA_Employee_ID,
                    COUNT(dm.`Account_ID`) AS No_of_Doctors,
                    COUNT(p.`Doctor_Id`) AS No_of_Doctors_profiled,
                    (SELECT 
                      SUM(target) AS Target_New_Rxn_for_the_month 
                    FROM
                      Rx_Target 
                    WHERE `Status` = 'Submitted' 
                      AND `Product_Id` = $product 
                      AND `Month` = $month 
                      AND `Year` = '$year' 
                      AND `VEEVA_Employee_ID` IN 
                      (SELECT 
                        VEEVA_Employee_ID 
                      FROM
                        Employee_Master 
                      WHERE Reporting_VEEVA_ID = em.Reporting_VEEVA_ID)) AS Target_New_Rxn_for_the_month,
                    SUM(rp.`Planned_Rx`) AS Planned_New_Rxn,
                    COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,
                    COUNT(
                      CASE
                        WHEN ar.`Activity_Done` = 'Yes' 
                        THEN 1 
                      END
                    ) AS checkk,
                    SUM(rxa.last_month_Rx) AS last_month_Rx,
                    SUM(rxa.current_month) AS current_month
                  FROM
                    (SELECT 
                      VEEVA_Employee_ID,`Reporting_To`,`Reporting_VEEVA_ID`
                    FROM
                      Employee_Master 
                    WHERE Reporting_VEEVA_ID IN 
                      (SELECT 
                        VEEVA_Employee_ID 
                      FROM
                        Employee_Master 
                      WHERE Reporting_VEEVA_ID = '$VEEVA_Employee_ID')) AS em 
                    LEFT JOIN 
                      (SELECT 
                        d.`Account_ID`,
                        ed.`VEEVA_Employee_ID` 
                      FROM
                        Doctor_Master d 
                        INNER JOIN Employee_Doc ed 
                          ON ed.`VEEVA_Account_ID` = d.`Account_ID` AND `ed`.`Status`='1'
                      WHERE `VEEVA_Employee_ID` IN 
                        (SELECT 
                          VEEVA_Employee_ID 
                        FROM
                          Employee_Master 
                        WHERE Reporting_VEEVA_ID IN 
                          (SELECT 
                            VEEVA_Employee_ID 
                          FROM
                            Employee_Master 
                          WHERE Reporting_VEEVA_ID = '$VEEVA_Employee_ID')) 
                        AND d.Individual_Type = '$Individual_Type') AS dm 
                      ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID 
                    LEFT JOIN 
                      (SELECT 
                        `Doctor_Id`,
                        `VEEVA_Employee_ID` 
                      FROM
                        Profiling 
                      WHERE `Product_Id` = $product 
                        AND STATUS = 'Submitted' AND Cycle={$this->Cycle}
                      GROUP BY `Doctor_Id`,
                        `VEEVA_Employee_ID`) AS p 
                      ON em.VEEVA_Employee_ID = p.VEEVA_Employee_ID 
                      AND dm.`Account_ID` = p.Doctor_Id 
                    LEFT JOIN 
                        (SELECT 
                          `Doctor_Id`,`VEEVA_Employee_ID`,`Planned_Rx`
                        FROM
                          `Rx_Planning` 
                        WHERE `Product_Id` = $product 
                          AND `Month` = $month 
                          AND `Year` = '$year' AND Planned_Rx > 0 ) AS rp 
                        ON dm.`Account_ID` = rp.`Doctor_Id` 
                        AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID 
                    LEFT JOIN 
                          (SELECT 
                            `Doctor_Id`,`VEEVA_Employee_ID`,Act_Plan 
                          FROM
                            `Activity_Planning` 
                          WHERE `Product_Id` = $product 
                            AND `Month` = $month 
                            AND `Year` = '$year') AS ap 
                          ON dm.`Account_ID` = ap.`Doctor_Id` 
                          AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                    LEFT JOIN 
                          (SELECT 
                            `Doctor_Id`,`VEEVA_Employee_ID`,`Activity_Done` 
                          FROM
                            `Activity_Reporting` 
                          WHERE `Product_Id` = $product 
                            AND `Month` = $month 
                            AND `Year` = '$year') AS ar 
                          ON dm.`Account_ID` = ar.`Doctor_Id` 
                          AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` 
                    LEFT JOIN 
                        (SELECT 
                          (
                            CASE
                              WHEN MONTH = {$month1} 
                              THEN SUM(Actual_Rx) 
                              ELSE 0 
                            END
                          ) AS last_month_Rx,
                          (
                            CASE
                              WHEN MONTH = {$month2} 
                              THEN SUM(Actual_Rx) 
                              ELSE 0 
                            END
                          ) AS current_month,
                          Doctor_Id,VEEVA_Employee_ID
                        FROM
                          Rx_Actual 
                        WHERE YEAR = '$year' AND month IN($month1,$month2) AND Actual_Rx > 0
                          AND Product_id = $product 
                        GROUP BY `Doctor_Id`,
                          `VEEVA_Employee_ID`) AS rxa 
                        ON em.VEEVA_Employee_ID = rxa.VEEVA_Employee_ID 
                        AND dm.`Account_ID` = rxa.Doctor_Id 
                  GROUP BY em.Reporting_VEEVA_ID ";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function insertLog($data) {
        $this->db->insert('log', $data);
    }

    function getLog($conditions = array()) {
        $sql = "SELECT em.Full_Name,em.VEEVA_Employee_ID,em.Zone,l.*,t.Territory FROM log l "
                . " INNER JOIN Employee_Master em ON em.VEEVA_Employee_ID = l.VEEVA_Employee_ID AND l.description <> '' LEFT JOIN Territory_master t ON em.Territory = t.id ";
        if (!empty($conditions)) {
            $sql .= join(" ", $conditions);
        }

        $sql .= " ORDER BY date DESC";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function getTabcontrol($conditions = array()) {
        $sql = "SELECT em.*,tb.Tab1,tb.Tab2,tb.Tab3,tb.Tab4 FROM Employee_Master em "
                . " LEFT JOIN Tab_Control tb ON em.VEEVA_Employee_ID = tb.VEEVA_Employee_ID ";
        if (!empty($conditions)) {
            $sql .= join(" ", $conditions);
        }

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function monthlyTrend($month = 1, $year = '2016', $product = 0, $conditions = array()) {
        $Individual_Type = $product == 1 ? 'Hospital' : 'Doctor';
        $sql = "SELECT em.`Full_Name`,em.VEEVA_Employee_ID,em.Zone,t.Territory,dm.Account_ID,dm.Account_Name,
                COUNT(ed.`VEEVA_Account_ID`) AS No_of_Doctors ,
                SUM(rp.`Planned_Rx`) AS Planned_New_Rxn,
                COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,
                COUNT(CASE WHEN ar.`Activity_Done`='Yes' THEN 1 END) AS checkk FROM Employee_Master em
                LEFT JOIN Territory_master t
                ON t.id = em.Territory
                INNER JOIN Employee_Doc ed 
                ON em.`VEEVA_Employee_ID`=ed.`VEEVA_Employee_ID` AND `ed`.`Status`='1'
                INNER JOIN Doctor_Master dm 
                ON dm.`Account_ID` = ed.`VEEVA_Account_ID` AND dm.Individual_Type = '$Individual_Type'
                INNER JOIN Rx_Planning rp ";
        if ($product > 0) {
            $sql .= "ON ed.`VEEVA_Account_ID`=rp.`Doctor_Id` AND rp.`Product_Id`=$product AND rp.`Month`=$month AND rp.`Year`='$year' AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID
                LEFT JOIN Activity_Planning ap
                ON ed.`VEEVA_Account_ID`=ap.`Doctor_Id` AND ap.`Product_Id`=$product AND ap.`Month`=$month AND ap.`Year`='$year' AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                LEFT JOIN Activity_Reporting ar
                ON ed.`VEEVA_Account_ID`=ar.`Doctor_Id` AND ar.`Product_Id`=$product AND ar.`Month`=$month AND ar.`Year`='$year' AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` 
                ";
        } else {
            $sql .= "ON ed.`VEEVA_Account_ID`=rp.`Doctor_Id`  AND rp.`Month`=$month AND rp.`Year`= '$year' AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID
                LEFT JOIN Activity_Planning ap
                ON ed.`VEEVA_Account_ID`=ap.`Doctor_Id` AND ap.`Month`=$month AND ap.`Year`='$year' AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                LEFT JOIN Activity_Reporting ar
                ON ed.`VEEVA_Account_ID`=ar.`Doctor_Id` AND ar.`Month`=$month AND ar.`Year`='$year' AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` 
                ";
        }

        if (!empty($conditions)) {
            $sql .=" WHERE " . join(" AND ", $conditions);
        }
        $sql .=" GROUP BY dm.`Account_ID` LIMIT 100";

        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    public function monthlyTrend2($month = 1, $year = '2016', $product = 0, $conditions = array(), $limit, $offset) {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $brandName = '';
        if ($product > 0) {
            $rpProduct = "AND Product_id = " . $product;
            $apProduct = "AND ap.Product_id = " . $product;
            $arProduct = "AND ar.Product_id = " . $product;
            $brandName = 'bm.Brand_Name';
            //$planProduct = "AND ar.Product_id = " . $product;
        }
        $sql = "SELECT em.`Zone`,t.Territory,em.Full_Name,em.`VEEVA_Employee_ID`,dm.`Account_ID`,dm.`Account_Name`,
                " . $brandName . ",
                COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,
                COUNT(CASE WHEN ar.`Activity_Done`='Yes' THEN 1 END) AS checkk,
                rp.Planned_Rx,
                rx.Jan,rx.Feb,rx.Mar,rx.Apr,rx.May,rx.Jun,rx.Jul,rx.Aug,rx.Sep,rx.Octo,rx.Nov,rx.Decb
                FROM
                ( SELECT 
                  `Doctor_Id`,`VEEVA_Employee_ID`,SUM(`Planned_Rx`) AS Planned_Rx,Product_id  
                FROM
                  `Rx_Planning` WHERE YEAR = '$year' " . $rpProduct . " GROUP BY `Doctor_Id`,VEEVA_Employee_ID ORDER BY VEEVA_Employee_ID LIMIT {$limit} OFFSET {$offset}
               ) AS rp 
                INNER JOIN `Doctor_Master` dm ON dm.`Account_ID` = rp.`Doctor_Id`
                INNER JOIN `Employee_Master` em ON rp.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID`
                LEFT JOIN (
                     SELECT `Doctor_Id`,`VEEVA_Employee_ID`,
                     SUM(CASE WHEN `month` = 01 THEN Actual_Rx ELSE 0 END) AS Jan,
                     SUM(CASE WHEN `month` = 02 THEN Actual_Rx ELSE 0 END) AS Feb,
                     SUM(CASE WHEN `month` = 03 THEN Actual_Rx ELSE 0 END) AS Mar,
                     SUM(CASE WHEN `month` = 04 THEN Actual_Rx ELSE 0 END) AS Apr,
                     SUM(CASE WHEN `month` = 05 THEN Actual_Rx ELSE 0 END) AS May,
                     SUM(CASE WHEN `month` = 06 THEN Actual_Rx ELSE 0 END) AS Jun,
                     SUM(CASE WHEN `month` = 07 THEN Actual_Rx ELSE 0 END) AS Jul,
                     SUM(CASE WHEN `month` = 08 THEN Actual_Rx ELSE 0 END) AS Aug,
                     SUM(CASE WHEN `month` = 09 THEN Actual_Rx ELSE 0 END) AS Sep,
                     SUM(CASE WHEN `month` = 10 THEN Actual_Rx ELSE 0 END) AS Octo,
                     SUM(CASE WHEN `month` = 11 THEN Actual_Rx ELSE 0 END) AS Nov,
                     SUM(CASE WHEN `month` = 12 THEN Actual_Rx ELSE 0 END) AS Decb
                     FROM `Rx_Actual` WHERE YEAR = '$year' " . $rpProduct . " GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`

                )  AS rx ON rp.`VEEVA_Employee_ID` = rx.`VEEVA_Employee_ID` AND rp.`Doctor_Id` = rx.`Doctor_Id`    
                   LEFT JOIN `Territory_master` t
                   ON t.`id` = em.`Territory`
                   LEFT JOIN Activity_Planning ap
                   ON dm.`Account_ID`=ap.`Doctor_Id`  AND ap.`Year`='$year' " . $apProduct . " AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                   LEFT JOIN Activity_Reporting ar
                   ON dm.`Account_ID`=ar.`Doctor_Id`  AND ar.`Year`='$year' " . $arProduct . " AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID`
                   LEFT JOIN Brand_Master bm ON bm.id = rp.Product_id            ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" GROUP BY rp.`Doctor_Id`,rp.`VEEVA_Employee_ID` ORDER BY em.VEEVA_Employee_ID ";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    public function ActivityTrend($start_date = '', $end_date = '', $year = '2016', $product = 0, $conditions = array()) {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $rpDate = '';
        $apDate = '';
        $arDate = '';
        $brandName = '"All"';

        if ($product > 0) {
            $rpProduct = "AND Product_id = " . $product;
            $apProduct = "AND Product_id = " . $product;
            $arProduct = "AND Product_id = " . $product;
            $brandName = "bm.Brand_Name";
        }

        if ($start_date != '' && $end_date != '') {
            $rpDate = "AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ";
            $apDate = "AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ";
            $arDate = "AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ";
        }

        $sql = "SELECT am.Activity_Name,em.`Zone`,t.Territory,em.Full_Name,em.`VEEVA_Employee_ID`," . $brandName . ",
                No_of_Doctors_planned,
                checkk,
                rp.Planned_Rx,                
                rx.Jan,rx.Feb,rx.Mar,rx.Apr,rx.May,rx.Jun,rx.Jul,rx.Aug,rx.Sep,rx.Octo,rx.Nov,rx.Decb
                FROM
                ( SELECT 
                  `Doctor_Id`,`VEEVA_Employee_ID`,SUM(`Planned_Rx`) AS Planned_Rx,Product_Id 
                    FROM
                  `Rx_Planning` WHERE YEAR = '$year' " . $rpProduct . " GROUP BY `Doctor_Id`,VEEVA_Employee_ID
               ) AS rp 
                INNER JOIN `Doctor_Master` dm ON dm.`Account_ID` = rp.`Doctor_Id`
                INNER JOIN `Employee_Master` em ON rp.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID`
                
                LEFT JOIN (
                     SELECT `Doctor_Id`,`VEEVA_Employee_ID`,
                     (CASE WHEN `month` = 01 THEN SUM(Actual_Rx) ELSE 0 END) AS Jan,
                     (CASE WHEN `month` = 02 THEN SUM(Actual_Rx) ELSE 0 END) AS Feb,
                     (CASE WHEN `month` = 03 THEN SUM(Actual_Rx) ELSE 0 END) AS Mar,
                     (CASE WHEN `month` = 04 THEN SUM(Actual_Rx) ELSE 0 END) AS Apr,
                     (CASE WHEN `month` = 05 THEN SUM(Actual_Rx) ELSE 0 END) AS May,
                     (CASE WHEN `month` = 06 THEN SUM(Actual_Rx) ELSE 0 END) AS Jun,
                     (CASE WHEN `month` = 07 THEN SUM(Actual_Rx) ELSE 0 END) AS Jul,
                     (CASE WHEN `month` = 08 THEN SUM(Actual_Rx) ELSE 0 END) AS Aug,
                     (CASE WHEN `month` = 09 THEN SUM(Actual_Rx) ELSE 0 END) AS Sep,
                     (CASE WHEN `month` = 10 THEN SUM(Actual_Rx) ELSE 0 END) AS Octo,
                     (CASE WHEN `month` = 11 THEN SUM(Actual_Rx) ELSE 0 END) AS Nov,
                     (CASE WHEN `month` = 12 THEN SUM(Actual_Rx) ELSE 0 END) AS Decb
                     FROM `Rx_Actual` WHERE YEAR = '$year' " . $rpProduct . " " . $rpDate . " GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`

                )  AS rx ON rp.`VEEVA_Employee_ID` = rx.`VEEVA_Employee_ID` AND rp.`Doctor_Id` = rx.`Doctor_Id`    
                
                LEFT JOIN `Territory_master` t
                ON t.`id` = em.`Territory`
                
                INNER JOIN (
                    SELECT COUNT(`Activity_Id`) AS No_of_Doctors_planned,VEEVA_Employee_ID,Activity_Id,Doctor_Id FROM Activity_Planning  WHERE `Year`='$year' " . $apProduct . " " . $apDate . " GROUP BY Activity_Id,VEEVA_Employee_ID

                ) AS ap ON em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` AND ap.Doctor_id = dm.`Account_ID`
                
                LEFT JOIN (
                    SELECT COUNT(CASE WHEN `Activity_Done`='Yes' THEN 1 END) AS checkk,VEEVA_Employee_ID,Activity_Id FROM Activity_Reporting  WHERE `Year`='$year' " . $arProduct . " " . $arDate . " GROUP BY Activity_Id,VEEVA_Employee_ID

                )AS ar ON em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` AND  ap.Activity_Id = ar.Activity_Id 
                   
                INNER JOIN `Activity_Master` am
                   ON am.`Activity_id` = ap.`Activity_Id`
                   LEFT JOIN Brand_Master bm ON bm.id = rp.Product_Id";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" GROUP BY am.Activity_id,em.VEEVA_Employee_ID ORDER BY em.VEEVA_Employee_ID ";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    public function DivisionWise($year = '2016', $product = 0, $conditions = array(), $start_date = '', $end_date = '') {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $dates = $start_date != '' && $end_date != '' ? " AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'" : '';
        $sql = "SELECT
                    em.`Zone`,
                    t.`Territory`,
                    em.`Full_Name`,                    
                    em.VEEVA_Employee_ID ,
                    CAST((pf.DPP4/2) AS decimal(15,0)) AS DPP4,
                    pf.SGLT,
                    plan.JardiancePlanned,
                    rx.jardiance_rx,
                    plan.TrajentaPlanned,
                    rx.trajenta_rx,
                    plan.TrajentaDuoPlanned,                                     
                    rx.trajentaduo_rx                   
                    
                FROM
                (SELECT 
                    * 
                  FROM
                    `Employee_Master` 
                  WHERE Division = 'Diabetes' 
                    AND `Profile` = 'BDM') AS em 
                LEFT JOIN
                (
                    SELECT 
                      VEEVA_Employee_ID,
                      SUM(CASE WHEN Product_id = 4 OR `Product_Id` = 6 THEN `Patient_Rxbed_In_Month` ELSE 0 END) AS DPP4,
                      SUM(CASE WHEN Product_id = 5 THEN `Patient_Rxbed_In_Month` ELSE 0 END) AS SGLT
                    FROM
                      `Profiling` 
                    WHERE `Product_Id` IN (4, 5, 6) 
                    GROUP BY `VEEVA_Employee_ID`
                ) as pf ON pf.VEEVA_Employee_ID = em.`VEEVA_Employee_ID`
                LEFT JOIN
                  (SELECT 
                    `VEEVA_Employee_ID`,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 4 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS TrajentaPlanned,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 5 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS JardiancePlanned,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 6 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS TrajentaDuoPlanned 
                  FROM
                    Rx_Planning 
                  WHERE Product_Id IN (4, 5, 6) " . $dates . " AND Planned_Rx > 0
                  GROUP BY `VEEVA_Employee_ID`) AS plan ON plan.VEEVA_Employee_ID = pf.VEEVA_Employee_ID
                  LEFT JOIN 
                    (SELECT 
                      `VEEVA_Employee_ID`,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 4 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS trajenta_rx,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 5 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS jardiance_rx,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 6 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS trajentaduo_rx 
                    FROM
                      `Rx_Actual` 
                    WHERE YEAR = '$year' AND Actual_Rx > 0
                      AND Product_id IN (4, 5, 6) " . $dates . "
                    GROUP BY `VEEVA_Employee_ID`) AS rx 
                    ON rx.VEEVA_Employee_ID = plan.VEEVA_Employee_ID 
                    LEFT JOIN `Territory_master` t ON em.`Territory`=t.`id`";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    public function DivisionWise2($year = '2016', $product = 0, $conditions = array(), $start_date = '', $end_date = '') {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $dates = $start_date != '' && $end_date != '' ? " AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'" : '';

        $sql = "SELECT
                em.`Zone`,
                t.`Territory`,
                em.`Full_Name`,
                em.VEEVA_Employee_ID,
                pf.STROKE,
                pf.NOAC,
                pf.THROMBO,
                plan.TrajentaPlanned,
                rx.trajenta_rx,
                plan.JardiancePlanned,
                rx.jardiance_rx,
                plan.TrajentaDuoPlanned,             
                rx.trajentaduo_rx                 
                 
                FROM 
                (SELECT 
                    * 
                  FROM
                    `Employee_Master` 
                  WHERE Division = 'Thrombi' 
                    AND `Profile` = 'BDM') AS em 
                LEFT JOIN
                (SELECT 
                        VEEVA_Employee_ID,
                      SUM(CASE WHEN Product_id = 1 THEN `Patient_Seen_month` ELSE 0 END) AS STROKE,
                      SUM(CASE WHEN Product_id = 2 THEN `Patient_Rxbed_In_Month` ELSE 0 END) AS NOAC,
                      SUM(CASE WHEN Product_id = 3 THEN `Patient_Rxbed_In_Month` ELSE 0 END) AS THROMBO
                    FROM
                      `Profiling` 
                    WHERE `Product_Id` IN (1, 2, 3) 
                    GROUP BY `VEEVA_Employee_ID` ) as pf ON pf.VEEVA_Employee_ID = em.`VEEVA_Employee_ID` 
                LEFT JOIN
                  (SELECT 
                    `VEEVA_Employee_ID`,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 1 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS TrajentaPlanned,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 2 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS JardiancePlanned,
                    SUM(
                      CASE
                        WHEN `Product_Id` = 3 
                        THEN `Planned_Rx` 
                        ELSE 0 
                      END
                    ) AS TrajentaDuoPlanned 
                  FROM
                    Rx_Planning 
                  WHERE  Product_Id IN (1, 2, 3) " . $dates . " AND Planned_Rx > 0
                  GROUP BY `VEEVA_Employee_ID`) AS plan ON plan.VEEVA_Employee_ID = em.VEEVA_Employee_ID
                  LEFT JOIN 
                    (SELECT 
                      `VEEVA_Employee_ID`,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 1 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS trajenta_rx,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 2 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS jardiance_rx,
                      SUM(
                        CASE
                          WHEN `Product_Id` = 3 
                          THEN Actual_Rx 
                          ELSE 0 
                        END
                      ) AS trajentaduo_rx 
                    FROM
                      `Rx_Actual` 
                    WHERE YEAR = '$year' AND Actual_Rx > 0
                      AND Product_id IN (1, 2, 3) " . $dates . "
                    GROUP BY `VEEVA_Employee_ID`) AS rx 
                    ON rx.VEEVA_Employee_ID = plan.VEEVA_Employee_ID 

                    LEFT JOIN `Territory_master` t ON em.`Territory`=t.`id`";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function dailyTrend($date, $year = '2016', $product = 0, $conditions = array(), $start_date, $end_date, $limit, $offset) {
        $date_array = array();
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $rxProduct = '';

        if ($product > 0) {
            $rpProduct = "AND Product_Id = " . $product;
            $apProduct = "AND ap.Product_Id = " . $product;
            $arProduct = "AND ar.Product_Id = " . $product;
            $rxProduct = "AND Product_Id = " . $product;
            $brandName = "bm.Brand_Name";
        }
        $start_date1 = $start_date;
        $start_date2 = $start_date;
        while (strtotime($start_date2) <= strtotime($end_date)) {
            array_push($date_array, "rx.d" . date('Ymd', strtotime($start_date2)) . "");
            $start_date2 = date("Y-m-d", strtotime("+1 day", strtotime($start_date2)));
        }
        $Individual_Type = $product == 1 ? 'Hospital' : 'Doctor';
        $sql = "SELECT 
                    em.Zone,
                    t.Territory,
                    em.`Full_Name`,
                    em.VEEVA_Employee_ID,  
                   
                    dm.Account_ID,
                    dm.Account_Name,
                    " . $brandName . ",
                    COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,
                    COUNT(
                      CASE
                        WHEN ar.`Activity_Done` = 'Yes' 
                        THEN 1 
                      END
                    ) AS checkk,
                    SUM(rp.`Planned_Rx`) AS Planned_New_Rxn,";
        if (!empty($date_array)) {
            $sql .= join(",", $date_array);
        }
        $sql.=" FROM
                    (SELECT 
                      * 
                    FROM
                      Rx_Planning 
                    WHERE YEAR = '$year' 
                       " . $rpProduct . "  AND DATE_FORMAT(created_at, '%Y-%m-%d') " . $date . "  ORDER BY VEEVA_Employee_ID LIMIT {$limit} OFFSET {$offset}  ) AS rp 
                    INNER JOIN(
                        SELECT `Full_Name`,`VEEVA_Employee_ID`,`Zone`,Territory,Division FROM `Employee_Master` WHERE `Profile` = 'BDM'
                    )  AS em ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` 
                    LEFT JOIN Territory_master t 
                      ON t.id = em.Territory 
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = rp.`Doctor_id` 
                      AND dm.Individual_Type = '$Individual_Type' 
                    LEFT JOIN Activity_Planning ap 
                      ON rp.`Doctor_Id` = ap.`Doctor_Id` 
                      AND ap.`Year` = '$year' 
                      AND DATE_FORMAT(ap.created_at, '%Y-%m-%d') " . $date . "
                      AND ap.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` 
                      " . $apProduct . "
                    LEFT JOIN Activity_Reporting ar 
                      ON rp.`Doctor_Id` = ar.`Doctor_Id` 
                      " . $arProduct . "
                      AND ar.`Year` = '$year' 
                      AND DATE_FORMAT(ar.created_at, '%Y-%m-%d')  " . $date . "
                      AND rp.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID`
                    LEFT JOIN Brand_Master bm ON rp.Product_id = bm.id
                    LEFT JOIN (
                        SELECT ";
        while (strtotime($start_date1) <= strtotime($end_date)) {
            $sql.= "SUM(CASE WHEN DATE_FORMAT(created_at, '%Y-%m-%d') = '$start_date1' THEN Actual_Rx ELSE 0 END ) AS 'd" . date('Ymd', strtotime($start_date1)) . "' , ";
            $start_date1 = date("Y-m-d", strtotime("+1 day", strtotime($start_date1)));
        }
        $sql .="Doctor_Id,VEEVA_Employee_ID from Rx_Actual  WHERE DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$start_date' and '$end_date' " . $rxProduct . "   GROUP BY Doctor_Id,VEEVA_Employee_ID ";
        $sql .= "   ) AS rx ON rx.VEEVA_Employee_ID = rp.VEEVA_Employee_ID AND rx.Doctor_Id = rp.Doctor_Id ";
        if (!empty($conditions)) {
            $sql .=" WHERE " . join(" AND ", $conditions);
        }

        $sql .=" GROUP BY dm.`Account_ID` ORDER BY em.VEEVA_Employee_ID";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function getTerritory1($condition = array()) {
        $sql = "SELECT t.id,t.Territory,em.Full_Name FROM Employee_Master em INNER JOIN Territory_master t ON t.id = em.Territory ";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function getTerritory2($condition) {
        $sql = "SELECT t.id,t.Territory FROM Territory_master t ";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    function getEmployeeDoctor($condition = array()) {
        $sql = "SELECT * FROM Employee_Doc ed INNER JOIN Employee_Master em ON ed.VEEVA_Employee_ID = em.VEEVA_Employee_ID INNER JOIN Doctor_Master dm ON dm.Account_ID = ed.VEEVA_Account_ID AND `ed`.`Status`='1' ";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" ", $condition);
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function Top10KPI($Product_Id = 0, $condition, $order_by = '', $start_date = '', $end_date = '') {
        $Product = $Product_Id > 0 ? "AND Product_Id = '" . $Product_Id . "'" : '';
        $between = ($start_date) != '' && ($end_date) != '' ? " AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' " : '';
        $between1 = ($start_date) != '' && ($end_date) != '' ? " WHERE DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' " : '';
        $sql = "SELECT 
                    tr.target,SUM(rx.Actual_Rx) AS Actual_Rx,
                    em.Zone,em.`Full_Name`,
                    t.Territory, IF(tr.target=0 OR tr.target = NULL, 0, (SUM(rx.Actual_Rx)/tr.target) * 100) AS KPI 
                 FROM
                    (SELECT 
                        SUM(target) AS target,VEEVA_Employee_ID 
                      FROM
                        Rx_Target WHERE STATUS = 'Submitted' " . $between . " " . $Product . "
                        GROUP BY `VEEVA_Employee_ID`) AS tr 
                    INNER JOIN Employee_Master em
                      ON em.VEEVA_Employee_ID = tr.VEEVA_Employee_ID
                    INNER JOIN Employee_Doc ed 
                      ON ed.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` AND `ed`.`Status`='1'
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
                    LEFT JOIN Territory_master t 
                      ON t.id = em.Territory
                    LEFT JOIN 
                      (SELECT 
                        SUM(Actual_Rx) AS Actual_Rx,`VEEVA_Employee_ID`,Doctor_Id 
                      FROM
                        Rx_Actual " . $between1 . " " . $Product . " AND Year = '$this->nextYear' AND Actual_Rx > 0
                      GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`
                        ) AS rx 
                      ON rx.Doctor_Id = dm.`Account_ID` 
                      AND rx.VEEVA_Employee_ID = ed.`VEEVA_Employee_ID`";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .= "GROUP BY em.VEEVA_Employee_ID " . $order_by;
        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        return $query->result();
    }

    function Top10ActivityKPI($Product_Id, $condition, $order_by = '', $start_date = '', $end_date = '') {
        $sql = "SELECT 
                    SUM(CASE WHEN rx.Activity_Done = 'Yes' THEN 1 ELSE 0 END) AS Doctor_engaged,COUNT(tr.Activity_Id) AS Doctor_Planned,
                    em.*,
                    t.Territory, 
                    IF(
                        COUNT(tr.Activity_Id) = 0 OR COUNT(tr.Activity_Id) = NULL, 0, 
                       (SUM(CASE WHEN rx.Activity_Done = 'Yes' THEN 1 ELSE 0 END)/COUNT(tr.Activity_Id)) * 100) AS KPI 
                 FROM
                    (SELECT 
                      * 
                    FROM
                      Employee_Master 
                    WHERE PROFILE = 'BDM') AS em 
                    INNER JOIN Employee_Doc ed 
                      ON ed.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` AND `ed`.`Status`='1'
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
                    LEFT JOIN 
                      (SELECT 
                        * 
                      FROM
                        Activity_Planning WHERE STATUS = 'Submitted'
                      GROUP BY Doctor_id,`VEEVA_Employee_ID`) AS tr 
                      ON em.`VEEVA_Employee_ID` = tr.VEEVA_Employee_ID AND dm.Account_Id = tr.Doctor_Id
                    LEFT JOIN Territory_master t 
                       ON t.id = em.Territory
                    LEFT JOIN 
                      (SELECT 
                        * 
                      FROM
                        Activity_Reporting WHERE STATUS = 'Submitted'
                      GROUP BY Doctor_id,`VEEVA_Employee_ID`) AS rx 
                      ON rx.Doctor_Id = dm.`Account_ID` 
                      AND rx.VEEVA_Employee_ID = ed.`VEEVA_Employee_ID`";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .= "GROUP BY em.VEEVA_Employee_ID " . $order_by;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function Top10BIShare($Product_Id, $condition, $order_by = '', $start_date = '', $end_date = '') {
        $Product = $Product_Id > 0 ? "AND Product_Id = '" . $Product_Id . "'" : '';
        $between = ($start_date) != '' && ($end_date) != '' ? " AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' " : '';
        $between1 = ($start_date) != '' && ($end_date) != '' ? " WHERE DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' " : '';

        $sql = "SELECT 
                    SUM(rx.Actual_Rx) AS Actual_Rx,em.*,t.Territory,
                    (CASE 
                        WHEN SUM(pf.Patient_Rxbed_In_Month)!=0 OR SUM(pf.Patient_Rxbed_In_Month) IS NOT NULL AND pf.Product_Id > 1 
                        THEN  SUM(rx.Actual_Rx) / SUM(pf.Patient_Rxbed_In_Month) * 100
                        ELSE 0 END) AS BIShare1,
                    (CASE 
                        WHEN SUM(pf.Patient_Seen)!=0 OR SUM(pf.Patient_Seen) IS NOT NULL AND pf.Product_Id = 1 
                        THEN  SUM(rx.Actual_Rx) / SUM(pf.Patient_Seen) * 100
                        ELSE 0 END) AS BIShare2
                 FROM
                    (SELECT 
                        SUM(Actual_Rx) AS Actual_Rx,`VEEVA_Employee_ID`,Doctor_Id 
                      FROM
                        Rx_Actual " . $between1 . " " . $Product . " AND year = '$this->nextYear'
                      GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`) AS rx 
                    INNER JOIN Employee_Master em ON rx.VEEVA_Employee_ID = em.VEEVA_Employee_ID
                    INNER JOIN Employee_Doc ed 
                      ON ed.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` AND rx.Doctor_Id = ed.VEEVA_Account_ID  AND `ed`.`Status`='1'
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
                    LEFT JOIN 
                      (SELECT   `Doctor_Id`,
                                `VEEVA_Employee_ID`,
                                `Patient_Seen`,
                                `Patient_Rxbed_In_Month`,
                                `Product_Id` 
                      FROM
                        Profiling WHERE STATUS = 'Submitted' " . $Product . "
                      GROUP BY Doctor_Id,`VEEVA_Employee_ID`) AS pf 
                      ON em.`VEEVA_Employee_ID` = pf.VEEVA_Employee_ID AND dm.Account_ID = pf.Doctor_Id
                    LEFT JOIN Territory_master t 
                       ON t.id = em.Territory ";
        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .= "GROUP BY em.VEEVA_Employee_ID " . $order_by;
        //echo $sql;

        $query = $this->db->query($sql);
        return $query->result();
    }

    function countDailyTrend($start_date, $end_date, $Product_Id, $Year, $condition = array()) {
        //$this->db->cache_on();
        $sql = "SELECT 
                    count(rp.Rxplan_id) AS PlanningCount
                  FROM
                    Rx_Planning rp INNER JOIN Employee_Master em ON em.VEEVA_Employee_ID = rp.VEEVA_Employee_ID
                  WHERE YEAR = '$Year' 
                    AND Product_Id = $Product_Id  AND DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ";
        if (!empty($condition)) {
            foreach ($condition as $value) {
                $sql .= " AND " . $value;
            }
        }
        $sql .=" ORDER BY em.VEEVA_Employee_ID";

        // echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function countMonthlyTrend($Product_Id, $Year, $condition = array()) {
        $sql = "SELECT 
                  count(rp.Rxplan_id) AS PlanningCount
                FROM
                  `Rx_Planning` rp INNER JOIN Employee_Master em ON em.VEEVA_Employee_ID = rp.VEEVA_Employee_ID
                  
        WHERE YEAR = '$Year' AND Product_Id = " . $Product_Id . "  ";
        if (!empty($condition)) {
            foreach ($condition as $value) {
                $sql .= " AND " . $value;
            }
        }
        $sql .=" ORDER BY em.VEEVA_Employee_ID";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->row();
    }

    public function cycle2report($VEEVA_Employee_ID, $Product_id, $month1, $month2, $month3, $year) {
        $sql = "SELECT 
            COUNT(Act_Plan) AS activity_actual 
            FROM
              (SELECT * FROM `Activity_Reporting` 
            WHERE `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
              AND `Product_id` = '$Product_id' 
              AND `Year` = '$year' 
              AND `month` IN($month1,$month2,$moth3) 
              AND `Status` = 'Submitted' 
              AND `Activity_Done` = 'Yes' ) AS rp
            INNER JOIN    Employee_Doc ed 
              ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
              INNER JOIN Doctor_Master Dm 
              ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID     ";
        $this->db->query($sql);
        return $query->row();
    }

    public function cycle2Activity($VEEVA_Employee_ID, $Product_id, $month1, $month2, $month3) {
        $sql = "SELECT 
                COUNT( rp.`Activity_Id`) AS activity_planned,
                COUNT(ar.Act_Plan) AS activity_actual 
                FROM
                 ( SELECT * FROM`Activity_Planning` 
                WHERE `month` IN ($month1, $month2, $month3) 
                  AND `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                  AND `Approve_Status` = 'Approved'
                  AND `Product_id` = '$Product_id'
                  AND `Year` = '2016' )AS rp
                   Left JOIN(SELECT * FROM `Activity_Reporting` 
                WHERE `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                   AND `Product_id` = '$Product_id' 
                   AND `Year` = '$this->nextYear' 
                   AND `month` IN ($month1, $month2, $month3)
                   AND `Approve_Status` = 'Approved' 
                   AND `Activity_Done` = 'Yes')  AS ar  ON ar.`VEEVA_Employee_ID`= rp.`VEEVA_Employee_ID` AND ar.Doctor_Id= rp.Doctor_id AND  `Activity_Done` = 'Yes'
                   INNER JOIN    Employee_Doc ed 
                  ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
                  INNER JOIN Doctor_Master Dm 
                  ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID  
                   ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function cycle1Actual($VEEVA_Employee_ID, $Product_id, $month1, $month2, $month3, $year) {
        $sql = "SELECT 
                SUM(`Planned_Rx`) AS planned_rx ,
                SUM(Actual_Rx) AS Actual_Rx 
              FROM
               ( SELECT * FROM `Rx_Planning` rp WHERE 

               `VEEVA_Employee_ID` = '$VEEVA_Employee_ID'
                AND `Approve_Status` = 'Approved' 
                AND `Product_id` = '$Product_id' 
                AND `Year` = '$year'
                AND  `month` IN ($month1, $month2, $month3) 
                ) AS rp
              LEFT JOIN  (SELECT 
                  * 
                FROM
                  `Rx_Actual` 
                WHERE `VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                  AND `Product_id` = '$Product_id' 
                  AND `month`IN($month1, $month2, $month3)  
                  AND `Year` = '2016' 
                  AND `Approve_Status` = 'Approved') AS ra ON rp.rp.`VEEVA_Employee_ID`=ra.`VEEVA_Employee_ID` AND ra.Doctor_id=rp.Doctor_id

                INNER JOIN    Employee_Doc ed 
                  ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
                  INNER JOIN Doctor_Master Dm 
                  ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID
                    ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function cycle1plan($VEEVA_Employee_ID, $Product_id, $month1, $month2, $month3, $year) {
        $sql = "SELECT 
                    SUM(`Planned_Rx`) AS planned_rx 
                    FROM
                   ( SELECT * FROM `Rx_Planning` rp WHERE 
                   `VEEVA_Employee_ID` = '$VEEVA_Employee_ID'
                    AND `Planning_Status` = 'Submitted' 
                    AND `Product_id` = '$Product_id' 
                    AND `Year` = '2016'
                    AND  `month` IN ( $month1, $month2, $month3) 
                    ) AS rp
                INNER JOIN    Employee_Doc ed 
                    ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND ed.Status = '1'  AND ed.VEEVA_Account_ID = rp.Doctor_id
                INNER JOIN Doctor_Master Dm 
                    ON ed.VEEVA_Account_ID = Dm.Account_ID AND  rp.Doctor_id = Dm.Account_ID";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function calculateMonth($current_month, $no_of_month) {
        $month = $current_month - $no_of_month;
        if ($month <= 0) {
            switch ($month) {
                case 0:
                    $month = 12;
                    break;
                case -1:
                    $month = 11;
                    break;
                case -2:
                    $month = 10;
                    break;
                case -3:
                    $month = 9;
                    break;
                case -4:
                    $month = 8;
                    break;
                case -5:
                    $month = 7;
                    break;
                case -6:
                    $month = 6;
                    break;
            }
        } else {
            $month = $month;
        }
        return $month;
    }

    function calculateYear($current_month, $no_of_month) {
        $month = $current_month - $no_of_month;
        if ($month <= 0) {
            $year = date('Y') - 1;
        } else {
            $year = date('Y');
        }
        return $year;
    }

    function getMonthName($month) {
        switch ($month) {
            case 1:
                $monthname = 'Jan';
                break;
            case 2:
                $monthname = 'Feb';
                break;
            case 3:
                $monthname = 'Mar';
                break;
            case 4:
                $monthname = 'Apr';
                break;
            case 5:
                $monthname = 'May';
                break;
            case 6:
                $monthname = 'Jun';
                break;
            case 7:
                $monthname = 'Jul';
                break;
            case 8:
                $monthname = 'Aug';
                break;
            case 9:
                $monthname = 'Sep';
                break;
            case 10:
                $monthname = 'Oct';
                break;
            case 11:
                $monthname = 'Nov';
                break;
            case 12:
                $monthname = 'Dec';
                break;
        }
        return $monthname;
    }

    function getMonthObject() {
        $Parameter = array();
        ///Parameter array
        $array = array(
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        );

        foreach ($array as $key => $value) {
            $Object = new stdClass();
            $Object->month = $key;
            $Object->monthname = $value;
            array_push($Parameter, $Object);
        }
        return $Parameter;
    }

    function getYearObject() {
        $Parameter = array();
        $array = array();

        $current_month = 2016;
        for ($i = 0; $i < 10; $i++) {
            $array[$current_month] = $current_month;
            $current_month ++;
        }
        foreach ($array as $key => $value) {
            $Object = new stdClass();
            $Object->Year = $key;
            $Object->Yearname = $value;
            array_push($Parameter, $Object);
        }
        return $Parameter;
    }

    function PasswordMail($data) {
        $this->db->insert('Send_Email', $data);
    }

    function CutOfDate() {
        $current_day = date('d');
        if ($current_day <= 3 ) {
            $current_month = date('n', strtotime('-1 month'));
            $created_at = date("Y-m-d", mktime(0, 0, 0, date("m"), 0, date("Y")));
        } else {
            $current_month = date('n');
            $created_at = date('Y-m-d H:i:s');
        }
        return array($current_month, $created_at);
    }

}
