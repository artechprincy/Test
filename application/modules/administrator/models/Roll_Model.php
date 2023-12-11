<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class roll_Model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_roll_list($class_id = null, $school_id = null)
    {

        $this->db->select('A.*, SC.school_name, C.name AS class_name, SE.name AS section');
        $this->db->from('student_roll AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = A.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = A.school_id', 'left');

        if ($class_id > 0) {
            $this->db->where('A.class_id', $class_id);
        }

        if ($school_id && $this->session->userdata('role_id') == SUPER_ADMIN) {
            $this->db->where('A.school_id', $school_id);
        }
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $this->db->where('A.school_id', $this->session->userdata('school_id'));
        }

        if ($this->session->userdata('role_id') == TEACHER) {
            $this->db->where('C.teacher_id', $this->session->userdata('profile_id'));
        }
        $this->db->where('SC.status', 1);
        $this->db->order_by('A.id', 'DESC');
        return $this->db->get()->result();
    }

    public function check_roll_no($school_id, $class_id, $section_id, $id = null)
    {
        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $query = $this->db->where(['school_id' => $school_id, 'class_id' => $class_id, 'section_id' => $section_id])->get('student_roll');
        return $query->num_rows();
    }

    public function duplicate_check($roll_no, $id = null)
    {
        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $query = $this->db->where(['start_roll' => $roll_no])->get('student_roll');
        return $query->num_rows();
    }
}
