<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admission_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    public function check_admission_no($adminssion_no)
    {
        $query = $this->db->get_where('students', ['admission_no'=>$adminssion_no]);
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}