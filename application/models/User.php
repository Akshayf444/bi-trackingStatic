<?php

class User extends My_model {

    public $table_name = 'user';

    function authenticate($username, $password) {
        $query = $this->db->get_where($this->table_name, array('psr_empid' => $username, 'password' => $password,));
        return $query->row_array();
    }

    function generateReport($fromdate, $todate) {
        /* $this->db->select('*');
          $this->db->from('user u');
          $this->db->join('doctor d', 'u.psr_empid = d.psr_id');
          $this->db->where('DATE_FORMAT(d.created_at,"%Y-%m-%d") >=', $fromdate);
          $this->db->where('DATE_FORMAT(d.created_at,"%Y-%m-%d") <=', $todate);
          $this->db->order_by('u.psr_empid'); */
        $query = $this->db->query("SELECT u.*,d.* FROM user u INNER JOIN `doctor` d ON u.psr_empid = d.psr_id WHERE DATE_FORMAT(d.created_at,'%Y-%m-%d') BETWEEN '$fromdate' AND '$todate'");
        // $query = $this->db->get();

        return $query->result();
    }

}
