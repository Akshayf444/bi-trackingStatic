<?php

class asm_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function rx_view($id) {
        $sql = " SELECT  * FROM Employee_Master WHERE Profile = 'BDM' AND `Reporting_VEEVA_ID`='$id'";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function product() {
        $sql = "SELECT  * FROM Brand_Master ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function planning_view($id, $product) {
        $sql = " 
                 SELECT Doctor_Master.`Account_Name`,Rx_Planning .* FROM  Doctor_Master  
                LEFT JOIN  Rx_Planning ON Doctor_Master.Account_ID= Rx_Planning.Doctor_Id
                 WHERE Rx_Planning.Product_Id='$product'
                  AND Rx_Planning.VEEVA_Employee_ID='$id' 
                  AND Rx_Planning.Planning_Status='submitted'
                  ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getTarget() {
        $this->db->select('*');
        $this->db->from('Employee_Master em');
        $this->db->join('Rx_Target rt', 'em.VEEVA_Employee_ID = rt.VEEVA_Employee_ID AND rt.month =  ' . $this->nextMonth . 'AND rt.Year = "' . $this->nextYear . '"', 'left');
        $this->db->where(array('rt.Product_Id' => $this->Product_Id, 'Reporting_VEEVA_ID' => $this->VEEVA_Employee_ID));
        $query = $this->db->get();
        echo $this->db->last_query();
        return $query->result();
    }

    public function report_rx($id, $product_id) {
        $sql = "SELECT `dm`.*,rt.Rxplan_id,rt.Approve_Status,SUM(rt.Actual_Rx) as Actual_Rx FROM (`Employee_Doc` ed) 
            INNER JOIN Doctor_Master dm ON ed.VEEVA_Account_ID = dm.Account_ID
            LEFT JOIN `Rx_Actual` rt ON `dm`.`Account_ID` = `rt`.`Doctor_Id` AND `rt`.`VEEVA_Employee_ID` = '$id' AND `rt`.`month` = '$this->nextMonth'  AND `rt`.`Year` = '$this->nextYear' AND `rt`.`Product_Id` = '$product_id' "
                . " WHERE   rt.Approve_Status = 'SFA'  OR rt.Approve_Status = 'Un-Approved' AND `ed`.`Status`='1'  GROUP BY rt.Doctor_Id  ORDER BY Actual_Rx DESC";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function approveReporting($VEEVA_Employee_ID, $Product_Id) {
        $this->db->select('*');
        $this->db->from('Employee_Doc ed');
        $this->db->join('Doctor_Master dm', 'ed.VEEVA_Account_ID = dm.Account_ID');
        $this->db->join('Rx_Actual er', '`dm`.`Account_ID` = `rt`.`Doctor_Id` AND `rt`.`VEEVA_Employee_ID` = "' . $VEEVA_Employee_ID . '" AND `rt`.`month` = "' . $this->nextMonth . '"  AND `rt`.`Year` = "' . $this->nextYear . '"');
        $this->db->where('Rx_Actual er', '`dm`.`Account_ID` = `rt`.`Doctor_Id` AND `ed`.`Status`=1  AND `rt`.`VEEVA_Employee_ID` = "' . $VEEVA_Employee_ID . '"   AND `rt`.`month` = "' . $this->nextMonth . '"  AND `rt`.`Year` = "' . $this->nextYear . '"');
    }

    public function report_Activity($id, $product_id) {
        $sql = "SELECT `dm`.*, `ar`.* FROM (`Employee_Doc` ed) 
            INNER JOIN Doctor_Master dm ON ed.VEEVA_Account_ID = dm.Account_ID
            LEFT JOIN `Activity_Reporting` ar ON `dm`.`Account_ID` = `ar`.`Doctor_Id` 
            WHERE `ar`.`Product_Id` = '$product_id' AND `ed`.`Status`='1'
            AND `ar`.`VEEVA_Employee_ID` = '$id' AND `ar`.`month` = '$this->nextMonth' AND `ar`.`Status` = 'Submitted' AND `ar`.`Year` = '$this->nextYear'";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function status_change($VEEVA_Employee_ID) {
        $query = $this->db->where('Act_Plan', $id);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function ASm($VEEVA_Employee_ID) {
        $sql = "SELECT em.`Full_Name`,em.`VEEVA_Employee_ID`,em.Local_Employee_ID FROM `Employee_Master` em
            WHERE `Reporting_VEEVA_ID`= '$VEEVA_Employee_ID'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function ASM_Assign_Target($VEEVA_Employee_ID, $product1, $product2, $product3) {
        $sql = "    (SELECT 
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        em.Local_Employee_ID,
                        rt.`target`,
                        rt.Status,
                        `Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product1 
                          AND MONTH = {$this->nextMonth} 
                          AND YEAR = '$this->nextYear' 
                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`) 
                      UNION
                      ALL 
                    (SELECT 
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        em.Local_Employee_ID,
                        rt.`target`,
                        rt.Status,
                        `Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product2 
                          AND MONTH = {$this->nextMonth} 
                          AND YEAR = '$this->nextYear' 
                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`) 
                      UNION
                      ALL 
                    (SELECT 
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        em.Local_Employee_ID,
                        rt.`target`,
                        rt.Status,
                        `Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product3
                          AND MONTH = {$this->nextMonth} 
                          AND YEAR = '$this->nextYear' 
                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`)";

        $query = $this->db->query($sql);
        //echo $this->db->last_query() . "<br/>";
        return $query->result();
    }

    function PlanningStatus($Product_Id) {
        if ($Product_Id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $sql = "SELECT 
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Planning_Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                   SELECT * FROM Employee_Master WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID'
                ) AS em 
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
                      WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID') AND d.Individual_Type = '$Individual_Type' ) AS dm 
                ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID 
                LEFT JOIN (SELECT * FROM  `Rx_Planning` WHERE month = {$this->nextMonth} AND Product_Id = {$Product_Id} AND Year = '$this->nextYear' GROUP BY VEEVA_Employee_ID,Doctor_Id ) rp 
                  ON rp.`Doctor_Id` = dm.`Account_ID` 
                  AND em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`    
              GROUP BY em.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function ActivityPlanningStatus($Product_Id,$month) {
        if ($Product_Id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $sql = "SELECT 
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                   SELECT * FROM Employee_Master WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID'
                ) AS em 
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
                      WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID') AND d.Individual_Type = '$Individual_Type' ) AS dm 
                ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID 
                LEFT JOIN (SELECT * FROM  `Activity_Planning` WHERE month = {$month} AND Product_Id = {$Product_Id} AND Year = '$this->nextYear' GROUP BY VEEVA_Employee_ID,Doctor_Id ) rp 
                ON rp.`Doctor_Id` = dm.`Account_ID` 
                AND em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`  

              GROUP BY em.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function ActivityReportingStatus($Product_Id, $month) {
        if ($Product_Id == 1) {
            $Individual_Type = 'Hospital';
        } else {
            $Individual_Type = 'Doctor';
        }
        $sql = "SELECT 
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
             FROM (
                   SELECT * FROM Employee_Master WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID'
                ) AS em 
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
                      WHERE Reporting_VEEVA_ID = '$this->VEEVA_Employee_ID') AND d.Individual_Type = '$Individual_Type' ) AS dm 
                ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID 
                LEFT JOIN (SELECT * FROM  `Activity_Reporting` WHERE month = {$month} AND Product_Id = {$Product_Id} AND Year = '$this->nextYear' GROUP BY VEEVA_Employee_ID,Doctor_Id ) rp 
                ON rp.`Doctor_Id` = dm.`Account_ID` 
                AND em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`  
                WHERE `Reporting_VEEVA_ID` = '$this->VEEVA_Employee_ID' 
              GROUP BY em.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function RxReportingStatus($Product_Id, $VEEVA_Employee_Id,$month="") {
        $sql = "SELECT 
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
              FROM
                `Rx_Actual` rp 
                WHERE  rp.`VEEVA_Employee_ID` = '$VEEVA_Employee_Id'
                  AND rp.`month` = {$month} 
                  AND Product_Id = {$Product_Id}                                     
                  AND YEAR = '$this->nextYear' ";
        $query = $this->db->query($sql);
        //echo $sql.'<br/>';
        return $query->result();
    }

    function insertComment($data) {
        $this->db->insert('Asm_Comment', $data);
    }

    function hospital_list($id, $conditions = array()) {


        $sql = "SELECT 
                    em.`Full_Name`,
                    em.`Reporting_VEEVA_ID`,
                    em.`Reporting_To`,
                    em.VEEVA_Employee_ID,
                    dm.VEEVA_Account_ID as Account_ID,
                    dm.`Account_Name` 
                   
                  FROM
                    (SELECT 
                      `VEEVA_Employee_ID`,`VEEVA_Account_ID`,`Account_Name`   
                    FROM
                      `Employee_Doc` 
                    WHERE `Status`='1' AND `VEEVA_Account_ID` IN 
                      (SELECT 
                        `Account_ID` 
                      FROM
                        `Doctor_Master` 
                      WHERE `Individual_Type` = 'Hospital')) AS dm 
                    INNER JOIN 
                      (SELECT 
                        `VEEVA_Employee_ID`,
                        Full_Name,
                        `Reporting_VEEVA_ID`,
                        Reporting_To,Zone
                      FROM
                        `Employee_Master` 
                      WHERE `Division` = 'thromBI' 
                        AND PROFILE = 'BDM' AND `Reporting_VEEVA_ID`= '$id' ) AS em ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID ";


        if (!empty($conditions)) {

            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" ";
//       echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function data_report($id) {
        $sql = "
            SELECT 
            *
            FROM
              Employee_Doc ed 
              LEFT JOIN Doctor_Master dm 
                ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
              LEFT JOIN Employee_Master em 
                ON em.`VEEVA_Employee_ID` = ed.`VEEVA_Employee_ID` 
              LEFT JOIN Profiling pr 

                ON pr.`Doctor_Id` = ed.`VEEVA_Account_ID` AND  pr.Cycle = {$this->Cycle}
             WHERE ed.`VEEVA_Account_ID` = '$id' AND `ed`.`Status`='1' ";


        $query = $this->db->query($sql);

        return $query->row();
    }

    function month1($id, $month, $emp = '') {
        $nextYear = ($this->nextYear - 1);
        $sql = "SELECT 
                rap.actual AS actual,
                rap.actualpast AS actualp,
                rp.Planned_Rx AS plan 
              FROM
                (SELECT 
                  Account_ID 
                FROM
                  Doctor_Master WHERE `Account_ID` = '$id'   AND STATUS = 'Active') AS dm 
                LEFT JOIN 
                  (SELECT 
                    VEEVA_Employee_ID,
                    VEEVA_Account_ID 
                  FROM
                    Employee_Doc 
                  WHERE VEEVA_Employee_ID = '$emp' 
                    AND VEEVA_Account_ID = '$id' AND `Status`='1') AS ed 
                  ON dm.`Account_ID` = ed.`VEEVA_Account_ID`  
                LEFT JOIN 
                  (SELECT 
                    Doctor_Id,
                    SUM(
                      CASE
                        WHEN `month` = '$month' 
                        AND `Year` = $this->nextYear 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS Planned_Rx 
                  FROM
                    Rx_Planning 
                  WHERE Doctor_Id = '$id') AS rp 
                  ON dm.`Account_ID` = rp.`Doctor_Id` 
                LEFT JOIN 
                  (SELECT 
                    Doctor_Id,
                    SUM(
                      CASE
                        WHEN `month` = '$month' 
                        AND `Year` = '$this->nextYear' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS actual,
                    SUM(
                      CASE
                        WHEN `month` = '$month' 
                        AND `Year` = '$nextYear' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS actualpast 
                  FROM
                    Rx_Actual 
                  WHERE Doctor_Id = '$id') AS rap 
                  ON dm.`Account_ID` = rap.`Doctor_Id` ";
//         echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function month2() {
        $sql = "select * from Rx_Actual where  Year='2015'And Month='1'";
        $query = $this->db->query($sql);
        return$query->result();
    }

    function month3() {
        $sql = "select * from Rx_Actual where  Year='2015'And Month='1'";
        $query = $this->db->query($sql);
        return$query->result();
    }

    function month4() {
        $sql = "select * from Rx_Actual where  Year='2015'And Month='1'";
        $query = $this->db->query($sql);
        return$query->result();
    }

    function present_data() {
        $sql = "select * from Rx_Actual where  Year='2016'";
        $query = $this->db->query($sql);
        return$query->result();
    }

    function activity_reportmonth($month, $id, $emp) {
        $sql = "SELECT 
                *,
                COUNT(Act_Plan) AS Act_Plan 
              FROM (
              SELECT * FROM Doctor_Master Dm WHERE Dm.Account_ID = '$id'  AND Dm.Status = 'Active'

              ) AS dm

                LEFT JOIN( SELECT * FROM Employee_Doc  where VEEVA_Account_ID='$id' And VEEVA_Employee_ID='$emp' AND `Status`='1') as ed
                  ON ed.VEEVA_Account_ID = dm.Account_ID 
                LEFT JOIN Activity_Planning ap 
                  ON ap.Doctor_id = dm.Account_ID 
              WHERE ap.`month` = $month
                AND ap.`Year` = '$this->nextYear'

                ";

        $query = $this->db->query($sql);
        return $query->row();
    }

    function PlanningStatus2($Product_Id, $month, $year, $condition = array()) {
        $sql = "SELECT 
                em.Zone,
                tm.Territory,
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                
                COUNT(
                  CASE
                    WHEN rp.`Planning_Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                    SELECT * FROM Rx_Planning WHERE month={$month} AND Product_Id = {$Product_Id} 
                    AND YEAR = '$year' 
                ) AS rp 
                INNER JOIN Employee_Doc ed
                    ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND rp.Doctor_id = ed.VEEVA_Account_ID AND `ed`.`Status`='1'
                INNER JOIN `Employee_Master` em 
                    ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` 
                LEFT JOIN Territory_master tm  
                    ON tm.id = em.Territory    ";

        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .=" GROUP BY em.`VEEVA_Employee_ID`";
        $query = $this->db->query($sql);
        //echo $this->db->last_query().';<br>';
        return $query->result();
    }

    function ActPlanningStatus2($Product_Id, $month, $year, $condition = array()) {
        $sql = "SELECT 
                em.Zone,
                em.`Full_Name`,
                tm.Territory,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                    SELECT * FROM Activity_Planning WHERE month={$month} AND Product_Id = {$Product_Id} 
                    AND YEAR = '$year' 
                ) AS rp 
                INNER JOIN Employee_Doc ed
                    ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND rp.Doctor_id = ed.VEEVA_Account_ID
                INNER JOIN `Employee_Master` em 
                    ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`
                LEFT JOIN Territory_master tm  
                    ON tm.id = em.Territory  ";

        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .=" GROUP BY em.`VEEVA_Employee_ID`";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function ActReportingStatus2($Product_Id, $month, $year, $condition = array()) {
        $sql = "SELECT 
                em.Zone,
                tm.Territory,
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                    SELECT * FROM Activity_Reporting WHERE month={$month} AND Product_Id = {$Product_Id} 
                    AND YEAR = '$year' 
                ) AS rp 
                INNER JOIN Employee_Doc ed
                    ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND rp.Doctor_id = ed.VEEVA_Account_ID
                INNER JOIN `Employee_Master` em 
                    ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`
                LEFT JOIN Territory_master tm  
                    ON tm.id = em.Territory ";

        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .=" GROUP BY em.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    function RxReportingStatus2($Product_Id, $month, $year, $condition = array()) {
        $sql = "SELECT 
                em.Zone,
                tm.Territory,
                em.`Full_Name`,
                em.`VEEVA_Employee_ID`,
                COUNT(
                  CASE
                    WHEN rp.`Status` = 'Submitted' 
                    THEN 1 
                  END
                ) AS SubmitCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Approved' 
                    THEN 1 
                  END
                ) AS ApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'Un-Approved' 
                    THEN 1 
                  END
                ) AS UnApproveCount,
                COUNT(
                  CASE
                    WHEN rp.`Approve_Status` = 'SFA' 
                    THEN 1 
                  END
                ) AS SFACount 
                FROM (
                    SELECT * FROM Rx_Actual WHERE month={$month} AND Product_Id = {$Product_Id} 
                    AND YEAR = '$year' 
                ) AS rp 
                INNER JOIN Employee_Doc ed
                    ON ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` AND rp.Doctor_id = ed.VEEVA_Account_ID
                INNER JOIN `Employee_Master` em 
                    ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID`
                LEFT JOIN Territory_master tm  
                    ON tm.id = em.Territory ";

        if (!empty($condition)) {
            $sql .= " WHERE " . join(" AND ", $condition);
        }

        $sql .=" GROUP BY em.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }

    public function find_actilyse_data($id, $doc_id) {
        $sql = "SELECT * FROM Doctor_Master dm 
                INNER JOIN  Actilyse_data ad ON ad.Doctor_id=dm.Account_ID 
                where ad.VEEVA_Employee_ID='$id' and ad.Doctor_id='$doc_id' and dm.Status='Active'";

        $query = $this->db->query($sql);

        return $query->row();
    }

}
