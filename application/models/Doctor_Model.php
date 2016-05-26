<?php

class Doctor_Model extends MY_model {

    public function __construct() {
        parent::__construct();
    }

    public function getDoctor($VEEVA_Employee_ID = 0, $type) {
        $this->db->select('dm.*');
        $this->db->from('Doctor_Master dm');
        $this->db->join('Employee_Doc ed', 'ed.VEEVA_Account_ID = dm.Account_ID  AND ed.Status=1');
        $this->db->where(array('ed.VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'dm.Individual_Type' => $type));
        $this->db->group_by("dm.Account_Name");
        $query = $this->db->get();
        return $query->result();
    }

    public function getProfiledDoctor($VEEVA_Employee_ID = 0, $Product_Id, $Individual_Type, $Cycle) {
        $sql = "SELECT 
                    dm.*,
                    (
                      CASE
                        WHEN pf.Status = 'Submitted' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS Profile_Status 
                  FROM
                    `Employee_Doc` ed 
                    INNER JOIN `Doctor_Master` dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` AND dm.Individual_Type = '$Individual_Type'
                    LEFT JOIN Profiling pf 
                      ON pf.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      AND pf.`Product_id` = {$Product_Id} 
                      AND pf.`Doctor_Id` = dm.`Account_ID` 
                      AND pf.Cycle = {$Cycle}
                  WHERE ed.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID'  AND `ed`.`Status`='1'  ";

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getProfilingDoctor($type) {
        $this->db->select('dm.*');
        $this->db->from('Doctor_Master dm');
        $this->db->join('Employee_Doc ed', 'ed.VEEVA_Account_ID = dm.Account_ID  AND ed.Status=1');
        $this->db->join('Profiling pf', 'ed.VEEVA_Account_ID = pf.Doctor_Id AND pf.Product_id =  ' . $this->Product_Id . ' AND pf.Cycle = ' . $this->Cycle, 'LEFT');
        $this->db->where(array('ed.VEEVA_Employee_ID' => $this->VEEVA_Employee_ID, 'dm.Individual_Type' => $type, 'pf.Doctor_id' => NULL));
        $this->db->group_by("dm.Account_Name");
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function CountDoctor($VEEVA_Employee_ID = 0, $type) {
        $this->db->select('COUNT(DISTINCT(dm.Account_ID)) AS DoctorCount');
        $this->db->from('Doctor_Master dm');
        $this->db->join('Employee_Doc ed', 'ed.VEEVA_Account_ID = dm.Account_ID  AND ed.Status=1');
        $this->db->where(array('ed.VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'dm.Individual_Type' => $type));
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    public function generateDoctorId($result) {
        $doctors = array();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($doctors, "'" . $value->Account_ID . "'");
            }
        }

        return $doctors;
    }

}
