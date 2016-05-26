<?php

include APPPATH . 'third_party/phpMailer/class.phpmailer.php';
include APPPATH . 'third_party/phpMailer/class.smtp.php';

class admin_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function login($email, $pass) {
        $query = $this->db->get_where('admin_master', array('email' => $email, 'password' => $pass,));
        return $query->row_array();
    }

    public function insert($data) {
        return $this->db->insert('Employee_Master', $data);
    }

    public function insert_territory($data) {
        $this->db->insert('Territory_master', $data);
        return $this->db->insert_id();
    }

    public function del_terr($id, $data) {
        $query = $this->db->where('id', $id);
        $query = $this->db->update('Territory_master', $data);
        return $query;
    }

    public function emp_view() {
        $sql = "select em.VEEVA_Employee_ID,em.Local_Employee_ID,em.First_Name,em.password,em.Middle_Name,em.Last_Name,em.Full_Name,em.Gender, em.Mobile,em.Email_ID,em.Username,em.Address_1,em.Address_2,em.City,em.State,  em.Division,em.Product,em.Zone,em.Region,em.Profile,em.Designation,em.Created_By,em.Created_Date,em.Modified_By,em.Modified_date,em.Date_of_Joining,DOB,em.Reporting_To,em.Reporting_VEEVA_ID,em.Reporting_Local_ID, em.Status as Statuss,tm.id,tm.territory, tm.Status from Employee_Master em LEFT join Territory_master tm on em.Territory = tm.id  ";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function territory() {
        $sql = "select * from  Territory_master where status='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function territory_view($name) {
        $sql = "select * from  Territory_master where territory='$name'and status='1'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function find_by_terrid($id) {
        $sql = "select * from  Territory_master where id='$id'and status='1'";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function update_terr($id, $data) {
        $query = $this->db->where('id', $id);
        $query = $this->db->update('Territory_master', $data);
        return $query;
    }

    public function find_by_empid($id) {
        $sql = "select * from Employee_Master  where VEEVA_Employee_ID='$id'   ";
//         echo $sql;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function find_zone() {
        $sql = "select distinct(Zone) as Zone from Employee_Master WHERE Zone IS NOT NULL AND Zone <> '' and Zone <> 'Oncology'  ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function find_name() {
        $sql = "select Distinct(Full_Name) as Full_Name,VEEVA_Employee_ID  from Employee_Master Where Full_Name IS NOT NULL AND Profile='BDM' AND Full_Name <> ''  AND VEEVA_Employee_ID IS NOT NULL AND VEEVA_Employee_ID <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_Division() {
        $sql = "select distinct(Division) as Division from Employee_Master WHERE Division IS NOT NULL AND Division <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_profile() {
        $sql = "select distinct(Profile) as Profile from Employee_Master WHERE Profile IS NOT NULL AND Profile <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reporting_to($profile) {
        $sql = "SELECT * FROM Employee_Master WHERE Profile='$profile' GROUP BY Reporting_VEEVA_ID ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reporting_id($reporting_to) {
        $sql = "SELECT  * FROM Employee_Master WHERE Reporting_To ='$reporting_to'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function zone_data($zone, $profile, $conditions = array()) {
        $sql = "select em.VEEVA_Employee_ID,em.Local_Employee_ID,em.First_Name,em.Middle_Name,em.Last_Name,em.Full_Name, em.Gender, em.Mobile,em.Email_ID,em.Username,em.Address_1,em.Address_2,em.City,em.State,  em.Division,em.Product,em.Zone,em.Region,em.Profile,em.Designation,em.Created_By,em.Created_Date,em.Modified_By,em.Modified_date,em.Date_of_Joining,DOB,em.Reporting_To,em.Reporting_VEEVA_ID,em.Reporting_Local_ID, em.Status as Statuss,tm.id,tm.territory as Territory, tm.Status from Employee_Master  em LEFT join Territory_master tm on em.Territory = tm.id ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }
//echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_region() {
        $sql = "select distinct(Region) as Region from Employee_Master WHERE Region IS NOT NULL AND Region <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_Designation() {
        $sql = "select distinct(Profile) as Designation from Employee_Master WHERE Profile IS NOT NULL AND Profile <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_territory() {
        $sql = "select distinct (Territory) AS Territory,id as ID from Territory_master where status='1' AND Territory IS NOT NULL AND Territory <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_REPORTING_TO() {
        $sql = "select distinct (Reporting_To) AS  Reporting_To from Employee_Master";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_REPORTING_TO_VALUE($NAME) {
        $sql = "select * from Employee_Master WHERE Reporting_To=$NAME";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function update_emp($id, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $id);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function del_emp($id, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $id);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function asm() {
        $sql = "select * from asm";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function asm_by_id($id) {
        $sql = "select * from asm
             where asm_id=$id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function asm_edit($id) {
        $sql = "select * from asm
                where asm_id=$id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function zsm_edit($id) {
        $sql = "select * from zsm
                where zsm_id=$id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function bdm_edit($id) {
        $sql = "select * from bdm
                where bdm_id=$id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function zsm() {
        $sql = "select * from zsm";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function bdm() {
        $sql = "select * from bdm";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insert_activity($data) {
        $data2 = array(
            'Activity_Name' => $data['Activity_Name']
        );

        $this->db->insert('Activities', $data2);

        $act_id = $this->db->insert_id();
        $data['compositekey'] = $act_id . '_' . $data['Product_ID'];
        $data['Activity_id'] = $act_id;
        if ($data['Product_ID'] == 4 || $data['Product_ID'] == 6) {

            $data['Product_ID'] = 4;
            $data['compositekey'] = $act_id . '_' . 4;
            $this->db->insert('Activity_Master', $data);
            $data['Product_ID'] = 6;
            $data['compositekey'] = $act_id . '_' . 6;
            $this->db->insert('Activity_Master', $data);
        } else {
            $this->db->insert('Activity_Master', $data);
        }
    }

    public function insert_doc($data) {
        return $this->db->insert('Doctor_Master', $data);
    }

    public function view_activity() {

        $sql = "SELECT Brand_Master.id, Brand_Master.Brand_Name,Activity_Master.*
                FROM Activity_Master LEFT JOIN  Brand_Master  ON Brand_Master.id=Activity_Master.Product_ID ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function show_pro_list() {
        $sql = "select * from Brand_Master";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function del_act($id, $data) {
        $query = $this->db->where('Activity_id', $id);
        $query = $this->db->update('Activity_Master', $data);
        return $query;
    }

    public function find_by_activityid($id) {
        $sql = "select * from Activity_Master where Activity_id='$id'  ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function update_act($id, $data) {
        if ($data['Product_ID'] == 4 || $data['Product_ID'] == 6) {
            $data['Product_ID'] = 4;
            $query = $this->db->where('Activity_id', $id);
            $query = $this->db->update('Activity_Master', $data);
            $data['Product_ID'] = 6;
            $query = $this->db->where('Activity_id', $id);
            $query = $this->db->update('Activity_Master', $data);
        } else {
            $query = $this->db->where('Activity_id', $id);
            $query = $this->db->update('Activity_Master', $data);
        }


        $data2 = array('Activity_Name' => $data['Activity_Name']);

        $query = $this->db->where('act_id', $id);
        $query = $this->db->update('Activities', $data2);
        return $query;
    }

    public function view_profile_controller() {
        $sql = "SELECT  Tab_Control.*,Employee_Master.Territory, Employee_Master.Zone,Employee_Master.Full_Name FROM Employee_Master Left Join Tab_Control on Employee_Master. VEEVA_Employee_ID=Tab_Control.VEEVA_Employee_ID GROUP BY Zone";
//        $sql = "select  Tab_Control.*, Employee_Master.Full_Name from Employee_Master left join Tab_Control on Employee_Master. VEEVA_Employee_ID=Tab_Control.VEEVA_Employee_ID ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function update_profile($id, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $id);
        $query = $this->db->update('Tab_Control', $data);
        return $query;
    }

    public function lock() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='0',`Tab2`='0',`Tab3`='0',`Tab4`='0',`Tab5`='0'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function unlock() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function active_profile($id, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $id);
        $query = $this->db->update('Tab_Control', $data);
        return $query;
    }

    public function doc_count() {
        $sql = "select count(Account_ID) as Account_Id from Doctor_Master ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function doc_view($limit, $offset) {
        $sql = "SELECT * FROM Doctor_Master  WHERE Status = 'Active' ";
        $sql .= "LIMIT $limit ";
        $sql .= " OFFSET $offset";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insert_csv($data) {
        return $this->db->insert('Employee_Master', $data);
    }

    public function insert_csv_doc($data) {
        return $this->db->insert('Doctor_Master', $data);
    }

    public function emp_duplicate($id, $username) {
        $sql = " select * from  Employee_Master WHERE VEEVA_Employee_ID='$id' OR Username='$username'";
//         echo $sql;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function empdoc_duplicate($id, $docid) {
        $sql = " select * from  Employee_Doc WHERE VEEVA_Employee_ID='$id' and VEEVA_Account_ID='$docid' AND `Status`='1' ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function doc_duplicate($docid) {
        $sql = " select * from  Doctor_Master WHERE Account_ID='$docid' ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function dr_by_product($division) {
        $sql = "SELECT COUNT(dm.Account_id) AS Division_dr FROM `Doctor_Master` dm
                INNER JOIN `Employee_Doc` ed
                ON ed.VEEVA_Account_ID=dm.Account_ID AND `ed`.`Status`='1'
                INNER JOIN `Employee_Master` em
                ON em.VEEVA_Employee_ID=ed.Local_Employee_ID
                WHERE em.Division='$division'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function total_target_by_product($product_id) {
        $sql = "SELECT SUM(`target`) AS total_target FROM `Rx_Target`
                WHERE `Product_Id`= $product_id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function update_doc($id, $data) {
        $query = $this->db->where('Account_ID', $id);
        $query = $this->db->update('Doctor_Master', $data);
        return $query;
    }

    public function del_doc($id, $data) {
        $query = $this->db->where('Account_ID', $id);
        $query = $this->db->update('Doctor_Master', $data);
        return $query;
    }

    public function find_by_docid($id) {
        $sql = "select * from Doctor_Master where Account_ID='$id'  ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function total_actualrx_by_product($product_id) {
        $sql = "SELECT SUM(`Actual_Rx`) AS total_actual_rx FROM `Rx_Actual`
                WHERE `Product_Id`= $product_id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function count() {
        $sql = "SELECT COUNT(Employee_Doc.VEEVA_Account_ID) AS COUNT FROM Employee_Master em  
                INNER JOIN Employee_Doc on Employee_Doc.VEEVA_Employee_ID = em.VEEVA_Employee_ID AND `Employee_Doc`.`Status`='1'
                INNER JOIN Doctor_Master dm ON dm.Account_ID = Employee_Doc.VEEVA_Account_ID";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function total_target($month, $Year) {
        $sql = "SELECT SUM(`target`) AS TOTAL FROM `Rx_Target` WHERE month = {$month} AND Year = '$Year' ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function total_convertion_by_product($product_id) {
        $sql = "SELECT COUNT(`Doctor_Id`) AS total_convertion FROM `Rx_Actual`
                WHERE `Product_Id`= $product_id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function total_convertion() {
        $sql = "SELECT COUNT(DISTINCT(`Doctor_Id`)) AS TOTAL FROM `Rx_Actual`";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function count_planned($month, $Year) {
        $sql = "SELECT SUM(Rx_Planning.Planned_RX) AS TOTAL FROM Employee_Master
            LEFT JOIN Rx_Planning ON Employee_Master.VEEVA_Employee_ID=Rx_Planning.VEEVA_Employee_ID
            WHERE Employee_Master.status='1' AND Rx_Planning.month = {$month} AND Rx_Planning.Year = '$Year'  ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function count_planned_month() {
        $sql = "SELECT SUM(Rx_Planning.Planned_RX) AS TOTAL FROM Employee_Master
            LEFT JOIN Rx_Planning ON Employee_Master.VEEVA_Employee_ID=Rx_Planning.VEEVA_Employee_ID
             WHERE Employee_Master.status='1' GROUP BY Rx_Planning.month  ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function count_achive($month, $Year) {
        $sql = "SELECT SUM(Rx_Actual.Actual_Rx) AS TOTAL FROM Rx_Actual
            WHERE month = {$month} AND Year = '$Year' ";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function count_achive_month() {
        $sql = "SELECT SUM(Rx_Actual.Actual_Rx) AS TOTAL FROM Rx_Actual
 LEFT JOIN Employee_Master ON Employee_Master.VEEVA_Employee_ID=Rx_Actual.VEEVA_Employee_ID
 WHERE Employee_Master.status='1' GROUP BY  Rx_Actual.month ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Tab1() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Tab2() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Tab3() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Tab4() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Tab5() {
        $sql = "UPDATE `Tab_Control` SET `Tab1`='1',`Tab2`='1',`Tab3`='1',`Tab4`='1',`Tab5`='1'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function count_doc($class) {
        $sql = "SELECT COUNT(DISTINCT(Specialty)) FROM Doctor_Master GROUP BY Specialty where Specialty=$class";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function BDM_show() {
        $sql = "SELECT em.`Full_Name`,em.`Region`,em.`State`,ra.`Actual_Rx`,ra.`Product_Id`,em.`VEEVA_Employee_ID` FROM `Employee_Master` em
            LEFT JOIN `Rx_Actual` ra
            ON em.`VEEVA_Employee_ID`=ra.`VEEVA_Employee_ID`
            WHERE em.`Profile`='BDM' 
            GROUP BY em.`VEEVA_Employee_ID`";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Over_all_count() {
        $sql = "SELECT COUNT(p.Doctor_Id) AS over_all FROM `Profiling` p
                WHERE p.Product_Id IN(1,2,3) AND Cycle = {$this->Cycle} ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function profiling_by_product($data) {
        $sql = "SELECT COUNT(p.Doctor_Id) AS profiling_by_product FROM `Profiling` p
                WHERE p.Product_Id = $data AND Cycle = {$this->Cycle}";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function insert_empdoc_csv($data) {
        return $this->db->insert('Employee_Doc', $data);
    }

    public function insert_tab($data) {
        return $this->db->insert('Tab_Control', $data);
    }

    public function bdm_unlocked_list() {
        $sql = "select * from  Employee_Master where Status ='locked' ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function emp_doc($id) {
        $sql = "select DM.Account_Name as Name,DM.Specialty,DM.Account_ID from Doctor_Master DM  "
                . "LEFT JOIN Employee_Doc ED ON DM.Account_ID=ED.VEEVA_Account_ID"
                . " where ED.VEEVA_Employee_ID ='$id' and DM.Status='Active' AND ED.Status='1' ";
        $query = $this->db->query($sql);
        //echo $sql;
        return $query->result();
    }

    public function reset_target($VEEVA_Employee_ID, $month, $year, $data) {
        $array = array('VEEVA_Employee_ID' => $VEEVA_Employee_ID, 'Month' => $month, 'Year' => $year);
        $query = $this->db->where($array);
        $query = $this->db->update('Rx_Target', $data);
        //echo $this->db->last_query();
        return $query;
    }

    public function del_empdoc($ID, $doc_id) {
        $data1 = array('VEEVA_Employee_ID' => $ID, 'VEEVA_Account_ID' => $doc_id);
        $query = $this->db->where($data1);
        $query = $this->db->delete('Employee_Doc');
        return $query;
    }

    public function del_docmaster($Account_ID, $data) {
        $query = $this->db->where('Account_ID', $Account_ID);
        $query = $this->db->update('Doctor_Master', $data);
        return $query;
    }

    public function target_view() {
        $sql = "Select * from Employee_Master Where Profile='ASM'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function Reporting_view() {
        $sql = "Select * from Employee_Master Where  Reporting_VEEVA_ID=''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function target_by_bdm() {
        $sql = "Select * from Rx_Target Where Profile='ASM'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function ASm_view($VEEVA_Employee_ID) {
        $sql = "SELECT em.Division, em.`Full_Name`,em.`VEEVA_Employee_ID`FROM `Employee_Master` em
            WHERE `Reporting_VEEVA_ID`= '$VEEVA_Employee_ID'";

        $query = $this->db->query($sql);
        return $query->result();
    }

    function ASM_division($VEEVA_Employee_ID) {
        $sql = "SELECT em.`Division` as division FROM `Employee_Master` em
                WHERE em.`VEEVA_Employee_ID`='$VEEVA_Employee_ID'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function ASM_Assign_Target($VEEVA_Employee_ID, $product1, $product2, $product3) {
        $sql = " (SELECT 
            em.`Division`,
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        rt.`target`,
                      rt.`Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product1 
                          AND MONTH = '$this->nextMonth' 
                          AND YEAR = '$this->nextYear' 
                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`) 
                      UNION
                      ALL 
                      (SELECT 
                       em.`Division`,
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        rt.`target`,
                         rt.`Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product2 
                          AND MONTH = '$this->nextMonth' 
                          AND YEAR = '$this->nextYear' 
                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`) 
                      UNION
                      ALL 
                      (SELECT 
                       em.`Division`,
                        em.`Full_Name`,
                        em.`VEEVA_Employee_ID`,
                        rt.`target`,
                         rt.`Product_Id` 
                      FROM
                        `Employee_Master` em 
                        LEFT JOIN `Rx_Target` rt 
                          ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                          AND `Product_id` = $product3
                            AND MONTH = '$this->nextMonth' 
                          AND YEAR = '$this->nextYear' 

                      WHERE em.`VEEVA_Employee_ID` = '$VEEVA_Employee_ID' 
                      GROUP BY em.`VEEVA_Employee_ID`)";

        $query = $this->db->query($sql);
//        echo $this->db->last_query() . "<br/>";
        return $query->result();
    }

    public function login_history() {
        $sql = "
                SELECT 
                    em.Full_Name,
                    em.Zone,
                    em.Profile,
                    em.Division,
                    tr.Territory,
                    lh.`created_at` as Last_Login,
                    lh.VEEVA_Employee_ID AS id,
                    COUNT(lh.VEEVA_Employee_ID) AS COUNT
                  FROM
                  (SELECT * FROM  login_history ORDER BY `created_at` DESC ) AS lh
                    INNER JOIN Employee_Master em 
                      ON em.`VEEVA_Employee_ID` = lh.`VEEVA_Employee_ID` 
                    LEFT JOIN Territory_master tr 
                      ON em.Territory = tr.id 
                  GROUP BY lh.`VEEVA_Employee_ID` ";
        $query = $this->db->query($sql);
//echo $sql;
        return $query->result();
    }

    public function login_view($id) {
        $sql = "select * FROM login_history WHERE VEEVA_Employee_ID ='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function adminDashboardCount($Product_Id, $month, $Year) {
        $individualType = (int) $Product_Id == 1 ? 'Hospital' : 'Doctor';
        $sql = "SELECT 
                    em.`Full_Name`,
                    em.VEEVA_Employee_ID,
                    COUNT(ed.`VEEVA_Account_ID`) AS No_of_Doctors,
                    COUNT(p.`Doctor_Id`) AS No_of_Doctors_profiled,
                    rt.`target` AS Target_New_Rxn_for_the_month,
                    SUM(rp.`Planned_Rx`) AS Planned_New_Rxn,
                    COUNT(ap.`Act_Plan`) AS No_of_Doctors_planned,
                    COUNT(
                      CASE
                        WHEN ar.`Activity_Done` = 'Yes' 
                        THEN 1 
                      END
                    ) AS checkk 
                  FROM
                    Employee_Master em 
                    LEFT JOIN Employee_Doc ed 
                      ON em.`VEEVA_Employee_ID` = ed.`VEEVA_Employee_ID` AND `ed`.`Status`='1'
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
                      AND dm.Individual_Type = '$individualType' 
                    LEFT JOIN Profiling p 
                      ON ed.`VEEVA_Account_ID` = p.`Doctor_Id` 
                      AND p.`Product_id` = {$Product_Id} 
                      AND p.Status = 'Submitted' 
                      AND p.Cycle = {$this->Cycle}
                    LEFT JOIN 
                      (SELECT 
                        * 
                      FROM
                        Rx_Target 
                      WHERE MONTH = {$month} 
                        AND YEAR = '$Year' 
                        AND `Product_Id` = {$Product_Id} 
                        AND STATUS = 'Submitted') AS rt 
                      ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                    LEFT JOIN Rx_Planning rp 
                      ON ed.`VEEVA_Account_ID` = rp.`Doctor_Id` 
                      AND rp.`Product_Id` = {$Product_Id}  
                      AND rp.`Month` = {$month} 
                      AND rp.`Year` = '$Year' 
                      AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID 
                    LEFT JOIN Activity_Planning ap 
                      ON ed.`VEEVA_Account_ID` = ap.`Doctor_Id` 
                      AND ap.`Product_Id` = {$Product_Id}   
                      AND ap.`Month` = {$month} 
                      AND ap.`Year` = '$Year' 
                      AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                    LEFT JOIN Activity_Reporting ar 
                      ON ed.`VEEVA_Account_ID` = ar.`Doctor_Id` 
                      AND ar.`Product_Id` = {$Product_Id} 
                      AND ar.`Month` = {$month} 
                      AND ar.`Year` = '$Year'  
                      AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` 
                  GROUP BY em.`VEEVA_Employee_ID` ";
//echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reporting_view2($id) {
        $sql = "select em.*,tr.* FROM Employee_Master em   LEFT JOIN Territory_master tr 
                    ON em.Territory = tr.id WHERE VEEVA_Employee_ID ='$id'  ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reporting_change($id, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $id);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function target_assign() {
        $sql = "SELECT 

                em.*,tm.`Territory`  
              FROM
                `Employee_Master` em
                INNER JOIN `Territory_master` tm ON tm.`id` = em.`Territory`

              WHERE `VEEVA_Employee_ID` IN 
                (SELECT DISTINCT 
                  (`Reporting_VEEVA_ID`) 
                FROM
                  Employee_Master em 
                  INNER JOIN `Rx_Target` rt 
                    ON rt.VEEVA_Employee_ID = em.VEEVA_Employee_ID 
                WHERE rt.Status = 'Submitted')";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function assign_bdm($profile, $zone, $division) {
        $sql = " select * from Employee_Master em  LEFT JOIN Territory_master tr 
                    ON em.Territory = tr.id where em.Profile='$profile' and em.Zone='$zone' AND em.Division='$division' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function assign_asm() {
        $sql = " select * from Employee_Master em  LEFT JOIN Territory_master tr 
                    ON em.Territory = tr.id where Profile='ASM'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function highcharts() {
        $sql = "SELECT SUM(Planned_Rx)AS planned FROM Rx_Planning rp WHERE rp.`month`='2' AND Product_Id='4'  
            UNION ALL
            SELECT SUM(Actual_Rx) AS actual FROM Rx_Actual ar
            WHERE ar.`month`='2'   AND Product_Id='4' 
            ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function assinged_bdmtoasm($VEEVA_Employee_ID, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $VEEVA_Employee_ID);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function assinged_zsmtoasm($VEEVA_Employee_ID, $data) {
        $query = $this->db->where('VEEVA_Employee_ID', $VEEVA_Employee_ID);
        $query = $this->db->update('Employee_Master', $data);
        return $query;
    }

    public function reports_filter_division() {
        $sql = "SELECT  DISTINCT (Division) AS Division FROM  Brand_Master WHERE Division IS NOT NULL AND Division <> '' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reports_filter_product() {
        $sql = "SELECT  DISTINCT (Brand_Name) AS Brand_Name  FROM Brand_Master WHERE Brand_Name IS NOT NULL AND Brand_Name <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_filter_data($zone, $region, $division, $product, $territory, $from, $to) {
        $sql = "SELECT em.`Zone`,em.`Region`,tr.`Territory`,em.`VEEVA_Employee_ID`,em.`Full_Name`,d.VEEVA_Account_ID,d.Account_Name,rp.`Planned_Rx`,ra.`Actual_Rx` FROM `Employee_Doc` d   
            Inner JOIN  Employee_Master em ON em.`VEEVA_Employee_ID`=d.`VEEVA_Employee_ID`
             Inner JOIN `Rx_Planning` rp ON em.`VEEVA_Employee_ID`= rp.`VEEVA_Employee_ID`
             LEFT JOIN `Territory_master` tr ON em.`Territory`= tr.`id`
             LEFT JOIN `Rx_Actual` ra ON  em.`VEEVA_Employee_ID`= ra.`VEEVA_Employee_ID`
              WHERE  em.Zone='$zone' OR em.`Region`='$region' OR em.`Division`='$division' OR  ra.`Product_Id`='$product' OR tr.Territory='$territory' OR ra.`created_at`
             BETWEEN '$from' AND '$to' AND `d`.`Status`='1';";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function adminDashboardCount2($Product_Id, $month, $Year) {
        $individualType = (int) $Product_Id == 1 ? 'Hospital' : 'Doctor';
        $sql = "SELECT 
                    em.`Full_Name`,
                    em.VEEVA_Employee_ID,
                    COUNT(ed.`VEEVA_Account_ID`) AS No_of_Doctors,
                    rt.`target` AS Target_New_Rxn_for_the_month,
                    SUM(rp.`Planned_Rx`) AS Planned_New_Rxn

                  FROM
                    Employee_Master em 
                    LEFT JOIN Employee_Doc ed 
                      ON em.`VEEVA_Employee_ID` = ed.`VEEVA_Employee_ID` AND `ed`.`Status`='1'
                    INNER JOIN Doctor_Master dm 
                      ON dm.`Account_ID` = ed.`VEEVA_Account_ID` 
                      AND dm.Individual_Type = '$individualType' 

                    INNER JOIN 
                      (SELECT 
                        * 
                      FROM
                        Rx_Target 
                      WHERE MONTH = {$month} 
                        AND YEAR = '$Year' 
                        AND `Product_Id` = {$Product_Id} 
                        AND STATUS = 'Submitted') AS rt 
                      ON em.`VEEVA_Employee_ID` = rt.`VEEVA_Employee_ID` 
                    LEFT JOIN Rx_Planning rp 
                      ON ed.`VEEVA_Account_ID` = rp.`Doctor_Id` 
                      AND rp.`Product_Id` = {$Product_Id}  
                      AND rp.`Month` = {$month} 
                      AND rp.`Year` = '$Year' 
                      AND rp.VEEVA_Employee_ID = em.VEEVA_Employee_ID 
                    WHERE em.Profile = 'BDM'
                  GROUP BY em.`VEEVA_Employee_ID` ";
//echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_specialty() {
        $sql = "select DISTINCT(Specialty) as Specialty  from Doctor_Master WHERE Specialty IS NOT NULL AND Specialty <> '' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function find_type() {
        $sql = "select DISTINCT(Individual_Type)as Individual_Type from Doctor_Master WHERE Individual_Type IS NOT NULL AND Individual_Type <> '' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function sendMail($email, $message) {
        echo $email . '<br/>';


        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

        $mail->IsSMTP(); // telling the class to use SMTP

        try {
            $mail->SMTPAuth = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "ssl";                 // sets the prefix to the server
            $mail->Host = "smtpout.asia.secureserver.net";      // sets the SMTP server
            $mail->Port = 465;                   // set the SMTP port for the MAIL server
            $mail->Username = "bisupport@instacom.in";  //  username
            $mail->Password = "bisupport";            // password

            $mail->FromName = "BI-Tracking";
            $mail->From = "bisupport@instacom.in";
            $mail->AddAddress($email, "BI-Tracking");
            //$mail->AddAddress('akshay@techvertica.com', "BI-Tracking");
            $mail->Subject = "Login Credentials";

            $mail->IsHTML(true);

            $mail->Body = <<<EMAILBODY

{$message}
EMAILBODY;

            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function find_state() {
        $sql = "select distinct(State) as State from Employee_Master WHERE State IS NOT NULL AND State <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getDashboardStatus($month = 1, $year = '2016', $product = 0, $conditions = array()) {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $planProduct = '';
        if ($product > 0) {

            $rpProduct = "AND Product_id = " . $product;
            $apProduct = "AND ap.Product_id = " . $product;
            $arProduct = "AND ar.Product_id = " . $product;
            $planProduct = "WHERE Product_id = " . $product;
        }
        $Individual_Type = isset($product) && $product == 1 ? 'Hospital' : 'Doctor';

        $sql = "SELECT 
                em.`VEEVA_Employee_ID`,
                em.Full_Name,
                em.`Zone`,
                ed.`Account_Name`,
                ed.`Account_ID`,
                t.Territory,
                SUM(ap.No_of_Doctors_planned) AS No_of_Doctors_planned,
                SUM(ar.checkk) AS checkk 
              FROM
                (SELECT 
                  d.`Account_ID`,
                  e.`VEEVA_Employee_ID`,
                  d.`Account_Name` 
                FROM
                  Doctor_Master d 
                  INNER JOIN Employee_Doc e 
                    ON e.`VEEVA_Account_ID` = d.`Account_ID` 
                    AND `e`.`Status` = '1' 
                WHERE d.`Individual_Type` = '$Individual_Type') AS ed 
                INNER JOIN 
                  (SELECT 
                    `VEEVA_Employee_ID`,
                    `Full_Name`,
                    `Zone`,
                    `Territory`,
                    Division
                  FROM
                    `Employee_Master` 
                  WHERE Profile = 'BDM') AS em 
                  ON ed.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` 

                LEFT JOIN `Territory_master` t 
                  ON t.`id` = em.`Territory` 
                LEFT JOIN 
                  (SELECT 
                    COUNT(`Act_Plan`) AS No_of_Doctors_planned,
                    Doctor_Id,
                    VEEVA_Employee_ID 
                  FROM
                    Activity_Planning 
                  WHERE `Year` = '$year' 
                    AND Product_id = $product 
                  GROUP BY Doctor_Id,
                    VEEVA_Employee_ID) AS ap 
                  ON ed.`Account_ID` = ap.`Doctor_Id` 
                  AND em.`VEEVA_Employee_ID` = ap.`VEEVA_Employee_ID` 
                LEFT JOIN 
                  (SELECT 
                    COUNT(
                      CASE
                        WHEN `Activity_Done` = 'Yes' 
                        THEN 1 
                      END
                    ) AS checkk,
                    Doctor_Id,
                    VEEVA_Employee_ID 
                  FROM
                    Activity_Reporting 
                  WHERE `Year` = '$year' 
                    AND Product_id = $product 
                  GROUP BY Doctor_Id,
                    VEEVA_Employee_ID) AS ar 
                  ON ed.`Account_ID` = ar.`Doctor_Id` 
                  AND em.`VEEVA_Employee_ID` = ar.`VEEVA_Employee_ID` ";

        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" ";
        $query = $this->db->query($sql);
        ///echo $sql . "<br/>";
        return $query->result();
    }

    public function getPlannedRx($Product_Id, $Year, $condition = array()) {
        $sql = "SELECT 
                SUM(rp.`Planned_Rx`) AS Planned_Rx 
              FROM
                `Employee_Doc` ed 
                INNER JOIN 
                    (SELECT 
                      Doctor_Id,VEEVA_Employee_ID,Planned_Rx 
                    FROM
                      `Rx_Planning` 
                    WHERE `Product_Id` = {$Product_Id} 
                      AND YEAR = '$Year' 
                      AND Planned_Rx > 0) AS rp 
                    ON rp.`Doctor_Id` = ed.`VEEVA_Account_ID` 
                    AND ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` 
                INNER JOIN `Doctor_Master` d ON d.`Account_ID` = ed.`VEEVA_Account_ID`
                INNER JOIN `Employee_Master` em 
                  ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` ";
        if (!empty($condition)) {
            $sql.= " WHERE " . join(" AND ", $condition);
        }
        // echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getActualRx($Product_Id, $Year, $condition = array()) {
        $sql = "SELECT 
                SUM(rp.`Actual_Rx`) AS Actual_Rx 
              FROM
                `Employee_Doc` ed 
                  INNER JOIN 
                    (SELECT 
                      Doctor_Id,VEEVA_Employee_ID,Actual_Rx 
                    FROM
                      `Rx_Actual` 
                    WHERE `Product_Id` = {$Product_Id} 
                      AND YEAR = '$Year' 
                      AND Actual_Rx > 0) AS rp 
                    ON rp.`Doctor_Id` = ed.`VEEVA_Account_ID` 
                    AND ed.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` 
                INNER JOIN `Doctor_Master` d ON d.`Account_ID` = ed.`VEEVA_Account_ID`
                INNER JOIN `Employee_Master` em 
                  ON em.`VEEVA_Employee_ID` = rp.`VEEVA_Employee_ID` ";
        if (!empty($condition)) {
            $sql.= " WHERE " . join(" AND ", $condition);
        }
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getDashboardStatus2($year = '2016', $product = 0, $conditions = array()) {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $planProduct = '';
        if ($product > 0) {
            $rpProduct = "AND Product_id = " . $product;
            $apProduct = "AND ap.Product_id = " . $product;
            $arProduct = "AND ar.Product_id = " . $product;
            $planProduct = "WHERE Product_id = " . $product;
        }
        $sql = "SELECT 
                    SUM(m1) AS m1,
                    SUM(m2) AS m2,
                    SUM(m3) AS m3,
                    SUM(m4) AS m4,
                    SUM(m5) AS m5,
                    SUM(m6) AS m6,
                    SUM(m7) AS m7,
                    SUM(m8) AS m8,
                    SUM(m9) AS m9,
                    SUM(m10) AS m10,
                    SUM(m11) AS m11,
                    SUM(m12) AS m12,
                    SUM(Ac1) AS Ac1,
                    SUM(Ac2) AS Ac2,
                    SUM(Ac3) AS Ac3,
                    SUM(Ac4) AS Ac4,
                    SUM(Ac5 ) AS Ac5,
                    SUM(Ac6) AS Ac6,
                    SUM(Ac7) AS Ac7,
                    SUM(Ac8) AS Ac8,
                    SUM(Ac9) AS Ac9,
                    SUM(Ac10) AS Ac10,
                    SUM(Ac11) AS Ac11,
                    SUM(Ac12) AS Ac12 
                  FROM
                    (SELECT 
                      SUM(
                      CASE
                        WHEN month = 1 OR month = 01
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m1,
                    SUM(
                      CASE
                        WHEN month = 2  OR month = 02
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m2,
                    SUM(
                      CASE
                        WHEN month = 3  OR month = 03
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m3,
                    SUM(
                      CASE
                        WHEN month = 4  OR month = 04
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m4,
                    SUM(
                      CASE
                        WHEN month = 5  OR month = 05
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m5,
                    SUM(
                      CASE
                        WHEN month = 6  OR month = 06
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m6,
                    SUM(
                      CASE
                        WHEN month = 7  OR month = 07
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m7,
                    SUM(
                      CASE
                        WHEN month = 8  OR month = 08
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m8,
                    SUM(
                      CASE
                        WHEN month = 9  OR month = 09
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m9,
                    SUM(
                      CASE
                        WHEN month = 10  
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m10,
                    SUM(
                      CASE
                        WHEN month = 11  
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m11,
                    SUM(
                      CASE
                        WHEN month = 12  
                        AND Year = '$year' 
                        THEN Planned_Rx 
                        ELSE 0 
                      END
                    ) AS m12,Doctor_Id,VEEVA_Employee_ID
                    FROM
                      `Rx_Planning` 
                    WHERE Product_id = {$product} AND Year = '$year'
                    GROUP BY `Doctor_Id`,
                      `VEEVA_Employee_ID`) rp 
                    INNER JOIN `Employee_Doc` ed 
                      ON rp.`Doctor_Id` = ed.`VEEVA_Account_ID` AND ed.VEEVA_Employee_ID = rp.VEEVA_Employee_ID AND ed.Status = 1              
                    INNER JOIN `Doctor_Master` dm 
                      ON dm.`Account_ID` = rp.`Doctor_Id` 
                    INNER JOIN `Employee_Master` em 
                      ON rp.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` 
                    LEFT JOIN 
                      (SELECT 
                         SUM(
                      CASE
                        WHEN month = 1 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac1,
                    SUM(
                      CASE
                        WHEN month = 2 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac2,
                    SUM(
                      CASE
                        WHEN month = 3 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac3,
                    SUM(
                      CASE
                        WHEN month = 4 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac4,
                    SUM(
                      CASE
                        WHEN month = 5 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac5,
                    SUM(
                      CASE
                        WHEN month = 6 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac6,
                    SUM(
                      CASE
                        WHEN month = 7 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac7,
                    SUM(
                      CASE
                        WHEN month = 8 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac8,
                    SUM(
                      CASE
                        WHEN month = 9 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac9,
                    SUM(
                      CASE
                        WHEN month = 10 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac10,
                    SUM(
                      CASE
                        WHEN month = 11 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac11,
                    SUM(
                      CASE
                        WHEN month = 12 
                        AND Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac12 ,`VEEVA_Employee_ID`,Doctor_Id,MONTH,YEAR
                      FROM
                        `Rx_Actual` 
                      WHERE YEAR = '$year' AND Actual_Rx > 0 
                        AND Product_id = {$product} GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`) AS rx 
                      ON rp.`VEEVA_Employee_ID` = rx.`VEEVA_Employee_ID` 
                      AND rp.`Doctor_Id` = rx.`Doctor_Id` ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" ";
        $query = $this->db->query($sql);
        //echo $sql . "<br/>";

        return $query->result();
    }

    public function getDashboardStatus3($year = '2016', $product = 0, $conditions = array()) {
        $rpProduct = '';
        $apProduct = '';
        $arProduct = '';
        $planProduct = '';
        if ($product > 0) {
            $rpProduct = "AND Product_id = " . $product;
            $apProduct = "AND ap.Product_id = " . $product;
            $arProduct = "AND ar.Product_id = " . $product;
            $planProduct = "WHERE Product_id = " . $product;
        }
        $sql = "SELECT 
                    SUM(m1) AS m1,
                    SUM(m2) AS m2,
                    SUM(m3) AS m3,
                    SUM(m4) AS m4,
                    SUM(m5) AS m5,
                    SUM(m6) AS m6,
                    SUM(m7) AS m7,
                    SUM(m8) AS m8,
                    SUM(m9) AS m9,
                    SUM(m10) AS m10,
                    SUM(m11) AS m11,
                    SUM(m12) AS m12,
                    SUM(
                      CASE
                        WHEN rx.month = 1 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac1,
                    SUM(
                      CASE
                        WHEN rx.month = 2 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac2,
                    SUM(
                      CASE
                        WHEN rx.month = 3 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac3,
                    SUM(
                      CASE
                        WHEN rx.month = 4 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac4,
                    SUM(
                      CASE
                        WHEN rx.month = 5 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac5,
                    SUM(
                      CASE
                        WHEN rx.month = 6 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac6,
                    SUM(
                      CASE
                        WHEN rx.month = 7 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac7,
                    SUM(
                      CASE
                        WHEN rx.month = 8 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac8,
                    SUM(
                      CASE
                        WHEN rx.month = 9 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac9,
                    SUM(
                      CASE
                        WHEN rx.month = 10 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac10,
                    SUM(
                      CASE
                        WHEN rx.month = 11 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac11,
                    SUM(
                      CASE
                        WHEN rx.month = 12 
                        AND rx.Year = '$year' 
                        THEN Actual_Rx 
                        ELSE 0 
                      END
                    ) AS Ac12 
                  FROM
                    (SELECT 
                      Doctor_Id,VEEVA_Employee_ID,
                    SUM(
                      CASE
                        WHEN month = 1 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m1,
                    SUM(
                      CASE
                        WHEN month = 2 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m2,
                    SUM(
                      CASE
                        WHEN month = 3 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m3,
                    SUM(
                      CASE
                        WHEN month = 4 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m4,
                    SUM(
                      CASE
                        WHEN month = 5 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m5,
                    SUM(
                      CASE
                        WHEN month = 6 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m6,
                    SUM(
                      CASE
                        WHEN month = 7 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m7,
                    SUM(
                      CASE
                        WHEN month = 8 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m8,
                    SUM(
                      CASE
                        WHEN month = 9 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m9,
                    SUM(
                      CASE
                        WHEN month = 10 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m10,
                    SUM(
                      CASE
                        WHEN month = 11 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m11,
                    SUM(
                      CASE
                        WHEN month = 12 
                        AND Year = '$year' 
                        THEN 1 
                        ELSE 0 
                      END
                    ) AS m12
                    FROM
                      `Activity_Planning` 
                    WHERE Product_id = {$product} AND Year = '$year' GROUP BY Doctor_Id,VEEVA_Employee_ID
                    ) AS rp 
                    INNER JOIN `Employee_Doc` ed 
                      ON rp.`Doctor_Id` = ed.`VEEVA_Account_ID` AND ed.VEEVA_Employee_ID = rp.VEEVA_Employee_ID AND ed.Status = 1              
                    INNER JOIN `Doctor_Master` dm 
                      ON dm.`Account_ID` = rp.`Doctor_Id` 
                    INNER JOIN `Employee_Master` em 
                      ON rp.`VEEVA_Employee_ID` = em.`VEEVA_Employee_ID` 
                    LEFT JOIN 
                      (SELECT 
                      
                        SUM(CASE WHEN Activity_Done = 'Yes' THEN 1 ELSE 0 END ) AS Actual_Rx,`VEEVA_Employee_ID`,Doctor_Id,MONTH,YEAR
                      FROM
                        `Activity_Reporting` 
                      WHERE YEAR = '$year' 
                        AND Product_id = {$product} GROUP BY `Doctor_Id`,`VEEVA_Employee_ID`) AS rx 
                      ON rp.`VEEVA_Employee_ID` = rx.`VEEVA_Employee_ID` 
                      AND rp.`Doctor_Id` = rx.`Doctor_Id` ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" ";
        $query = $this->db->query($sql);
        //echo $sql . "<br/>";

        return $query->result();
    }

    function tendays_rx() {
        $date = date('Y-m-d');
        $date1 = date('Y-m-d') - 9;
        $sql = " SELECT
          SUM(CASE WHEN `created_at` BETWEEN $date1 AND $date THEN Actual_Rx ELSE 0 END)AS m1 FROM Rx_Actual";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function tendays_plan() {
        $date = date('Y-m-d');
        $date1 = date('Y-m-d') - 9;
        $sql = " SELECT
          SUM(CASE WHEN `created_at` BETWEEN $date1 AND $date THEN Actual_ ELSE 0 END)AS m1 FROM Rx_Actual";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function emp_docmaster($limit, $offset) {
        $sql = " 
        SELECT em.Full_Name,em.Zone,tr.Territory,DM.Account_Name AS NAME,DM.Specialty,DM.Account_ID FROM  Employee_Doc ed 
         INNER JOIN Doctor_master DM ON ed.`VEEVA_Account_ID`=DM.`Account_ID` 
      INNER JOIN Employee_Master em ON em.VEEVA_Employee_ID =ed.`VEEVA_Employee_ID`
      LEFT JOIN Territory_master tr ON tr.id=ed.`Territory` WHERE Dm.`Status`='Active' AND `ed`.`Status`='1'
                LIMIT $limit 
        OFFSET $offset";


        $query = $this->db->query($sql);
        return $query->result();
    }

    function namefilter($id) {
        $sql = "SELECT em.Full_Name,em.Zone,tr.Territory,ed.Relationship_ID,DM.Account_Name AS NAME,DM.Specialty,DM.Account_ID,em.VEEVA_Employee_ID,DM.Individual_Type FROM  Employee_Doc ed 
           INNER JOIN Doctor_Master DM ON ed.`VEEVA_Account_ID`=DM.`Account_ID` 
           INNER JOIN Employee_Master em ON em.VEEVA_Employee_ID =ed.`VEEVA_Employee_ID`
           LEFT JOIN Territory_master tr ON tr.id=ed.`Territory` WHERE DM.`Status`='Active' AND `ed`.`Status`='1' AND em.VEEVA_Employee_ID='$id' AND em.VEEVA_Employee_ID IS NOT NULL AND em.VEEVA_Employee_ID <> ''";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function addTerritory($data) {
        $this->db->insert('Territory_master', $data);
        return $this->db->insert_id();
    }

    function getTerritoryArray() {
        $territory = array();
        $list = $this->territory();
        if (!empty($list)) {
            foreach ($list as $value) {
                array_push($territory, $value->Territory);
            }
        }

        return $territory;
    }

    function getTargetAssigned($month, $Year, $conditions = array()) {
        $sql = "SELECT 
                em.*,tm.`Territory` 
              FROM
                `Employee_Master` em
                INNER JOIN `Territory_master` tm ON tm.`id` = em.`Territory`

              WHERE `VEEVA_Employee_ID` IN 
                (SELECT DISTINCT 
                  (`Reporting_VEEVA_ID`) 
                FROM
                  Employee_Master em 
                  INNER JOIN `Rx_Target` rt 
                    ON rt.VEEVA_Employee_ID = em.VEEVA_Employee_ID 
                WHERE rt.Status = 'Submitted' AND MONTH = '$month' AND Year = '$Year' )";
        if (!empty($conditions)) {
            $sql .= join(" ", $conditions);
        }
        $sql .= " ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function getDatesArray($start_date, $end_date) {
        $dates = array();
        if ($start_date != '' && $end_date != '') {
            while (strtotime($start_date) <= strtotime($end_date)) {
                array_push($dates, date('Y-m-d', strtotime($start_date)) . "");
                $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
        }
        return $dates;
    }

    public function find_hospital($conditions = array()) {

        $sql = "SELECT 
                    em.`Full_Name`,
                    em.`Reporting_VEEVA_ID`,
                    em.`Reporting_To`,
                    em.VEEVA_Employee_ID,
                    dm.VEEVA_Account_ID,
                    dm.`Account_Name` AS NAME,
                    em.VEEVA_Employee_ID
                  FROM
                    (SELECT 
                      `VEEVA_Employee_ID`,`VEEVA_Account_ID`,`Account_Name` 
                    FROM
                      `Employee_Doc` 
                    WHERE `VEEVA_Account_ID` IN 
                      (SELECT 
                        `Account_ID` 
                      FROM
                        `Doctor_Master` 
                      WHERE `Individual_Type` = 'Hospital')AND `Status`='1')  AS dm 
                    INNER JOIN 
                      (SELECT 
                        `VEEVA_Employee_ID`,
                        Full_Name,
                        `Reporting_VEEVA_ID`,
                        Reporting_To,Zone
                      FROM
                        `Employee_Master` 
                      WHERE `Division` = 'thromBI' 
                        AND PROFILE = 'BDM' ) AS em ON em.VEEVA_Employee_ID = dm.VEEVA_Employee_ID ";

        if (!empty($conditions)) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        $sql.=" ";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function selectDistinctVeevaIDS() {
        $veevaid = array();
        $sql = "SELECT DISTINCT(VEEVA_Employee_ID) AS VEEVA_Employee_ID FROM Employee_Master UNION ALL SELECT DISTINCT(Username) AS VEEVA_Employee_ID FROM Employee_Master  ";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($veevaid, $value->VEEVA_Employee_ID);
            }
        }

        return $veevaid;
    }

}
