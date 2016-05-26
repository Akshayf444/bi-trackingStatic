<?php

class Master_model extends MY_model {

    public function __construct() {
        parent::__construct();
    }

    /*  param $result = rows to be converted to dropdown
     *  param $fiedid = primary key of row
     *  param $fieldname = name to shown in dropdown
     *  param $custom_attribute = additional data-attributes 
     *  param $custom_field = Name Of Column For data-attribute 
     * */

    function generateDropdown($result, $fieldid, $fieldname, $id = 0, $custom_attribute = array()) {
        $dropdown = '';
        $custom_field = '';
        if (!empty($result)) {
            foreach ($result as $item) {
                if (!empty($custom_attribute)) {
                    foreach ($custom_attribute as $key => $value) {
                        $custom_field .= $key . '="' . $item->{$value} . '" ';
                    }
                    //echo $custom_field."<br/>";
                }
                if ($id === $item->{$fieldid}) {
                    $dropdown .= '<option value="' . $item->{$fieldid} . '" selected ' . $custom_field . ' >' . $item->{$fieldname} . '</option>';
                } else {
                    $dropdown .= '<option value="' . $item->{$fieldid} . '" ' . $custom_field . '>' . $item->{$fieldname} . '</option>';
                }

                $custom_field = '';
            }
        }

        $dropdown .= '';
        return $dropdown;
    }

    function generateProfileDropdown($result) {
        $dropdown = '';
        foreach ($result as $item) {
            $custom_field = isset($item) && $item->Profile_Status == 1 ? 'class="Profiled"' : '';
            $field_name = isset($item) && $item->Profile_Status == 1 ? $item->Account_Name . ' *' : $item->Account_Name;
            $dropdown .= '<option value="' . $item->Account_ID . '" ' . $custom_field . '>' . $field_name . '</option>';


            $custom_field = '';
        }
        return $dropdown;
    }

    function BrandList($Division) {
        $this->db->select('*');
        $this->db->from('Brand_Master');
        $this->db->where('Division', $Division);
        $query = $this->db->get();
        return $query->result();
    }

    function getQuestions($Product_Id = 0) {
        $this->db->select('*');
        $this->db->from('Question_Master');
        $this->db->where('Product_Id', $Product_Id);
        $query = $this->db->get();
        return $query->result();
    }

    function DisplayAlert($message = "", $type = 'success') {
        $html = "<script>setTimeout(function() {
                    $.bootstrapGrowl('" . $message . "', {
                        type: '" . $type . "',
                        align: 'center',
                        width: 'auto',
                        allow_dismiss: true
                    });
                }, 2000);</script>";
        return $html;
    }

}
