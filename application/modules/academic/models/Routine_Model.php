<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Routine_Model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_section_list($class_id = null, $school_id = null)
    {

        if (!$class_id) {
            return;
        }

        $this->db->select('S.*, C.name AS class_name');
        $this->db->from('sections AS S');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->where('S.class_id', $class_id);


        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $this->db->where('S.school_id', $this->session->userdata('school_id'));
        }

        if ($school_id && $this->session->userdata('role_id') == SUPER_ADMIN) {
            $this->db->where('S.school_id', $school_id);
        }

        return $this->db->get()->result();
    }

    public function get_single_routine($id)
    {

        $this->db->select('S.*, C.name AS class_name, T.name AS teacher');
        $this->db->from('subjects AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->where('S.id', $id);
        return $this->db->get()->row();
    }

    function duplicate_routine($condition, $id = null)
    {
        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where($condition);
        return $this->db->get('routines')->num_rows();
    }

    public function get_year_list($school_id = null)
    {

        $this->db->select('AY.*, S.school_name');
        $this->db->from('academic_years AS AY');
        $this->db->join('schools AS S', 'S.id = AY.school_id', 'left');

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $this->db->where('AY.school_id', $this->session->userdata('school_id'));
        }

        if ($school_id && $this->session->userdata('role_id') == SUPER_ADMIN) {
            $this->db->where('AY.school_id', $school_id);
        }

        $this->db->where('S.status', 1);
        $this->db->order_by('AY.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_time_table_list($class_id = null, $school_id = null)
    {
        // if (!$class_id) {
        //     return;
        // }

        $this->db->select('T.*, C.name AS class_name, S.name AS sections_name, A.session_year');
        $this->db->from('time_table AS T');
        $this->db->join('classes AS C', 'C.id = T.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = T.section_id', 'left');
        $this->db->join('academic_years AS A', 'A.id = T.academic_year_id', 'left');
        // $this->db->where('S.class_id', $class_id);


        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $this->db->where('T.school_id', $this->session->userdata('school_id'));
        }

        if ($school_id && $this->session->userdata('role_id') == SUPER_ADMIN) {
            $this->db->where('T.school_id', $school_id);
        }

        return $this->db->get()->result();
    }

    public function get_routines_list($time_table_id)
    {
        $this->db->select('t1.*, t2.name as subject_name, t3.name as teacher_name');
        $this->db->from('routines AS t1');
        $this->db->join('subjects AS t2', 't2.id = t1.subject_id', 'left');
        $this->db->join('teachers AS t3', 't3.id = t1.teacher_id', 'left');
        $this->db->where('t1.time_table_id', $time_table_id);

        return $this->db->get()->result();
    }

    public function checkTimeTable($time_table_id, $start_time, $id = null)
    {
        $this->db->where('id', $time_table_id);
        $this->db->where('start_time >=', $start_time);
        $this->db->where('end_time <=', $start_time);
        return $this->db->get('time_table')->num_rows();
    }

    public function checkBeetween($time_table_id, $day, $time, $id = null)
    {        
        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where_in('day', $day);
        $this->db->where('time_table_id', $time_table_id);
        $this->db->where('start_time <=', $time);
        $this->db->where('end_time >=', $time);
        return $this->db->get('routines')->num_rows();
    }
}
