<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incomehead_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
        
     public function get_incomehead_list($school_id = null){
        
        $this->db->select('H.*, S.school_name');
        $this->db->from('income_heads AS H');
        $this->db->join('schools AS S', 'S.id = H.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $this->db->where('H.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
        
        $this->db->where('H.head_type', 'income');
        $this->db->where('S.status', 1);
        $this->db->order_by('H.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    function duplicate_check($school_id, $title, $head_type, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('head_type', $head_type);
        $this->db->where('title', $title);
        return $this->db->get('income_heads')->num_rows();            
    }

}