<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('Teacher_Model', 'teacher', true);

        // need to check school subscription status
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            if (!check_saas_status($this->session->userdata('school_id'), 'is_enable_attendance')) {
                redirect('dashboard/index');
            }
        }
    }


    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Teacher Attendance" user interface                 
     *                    and Process to manage daily Teacher attendance    
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index()
    {

        check_permission(VIEW);

        if ($_POST) {
            $school_id = $this->input->post('school_id');

            $school = $this->teacher->get_school_by_id($school_id);
            if (!$school->academic_year_id) {
                error($this->lang->line('set_academic_year_for_school'));
                redirect('attendance/teacher/index');
            }
            $academic_year_id = $school->academic_year_id;

            $this->data['teachers'] = $this->teacher->get_teacher_list($school_id);
            $condition['school_id'] = $school_id;
            $data = $condition;
            $data['academic_year_id'] = $academic_year_id;
            $data['status'] = 1;

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('to_date');
            $start_date = new DateTime($start_date);
            $end_date = new DateTime($end_date);

            while ($start_date <= $end_date) {
                if (!empty($this->data['teachers'])) {
                    foreach ($this->data['teachers'] as $obj) {
                        $condition['teacher_id'] = $obj->id;
                        $start_month = date('m', strtotime($start_date->format('Y-m-d')));
                        $start_year = date('Y', strtotime($start_date->format('Y-m-d')));
                        $data['month'] = $start_month;
                        $data['year'] = $start_year;
                        $condition['month'] = $start_month;
                        $condition['year'] = $start_year;
                        $attendance = $this->teacher->get_single('teacher_attendances', $condition);
                        if (empty($attendance)) {
                            $data['teacher_id'] = $obj->id;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            $data['created_by'] = logged_in_user_id();
                            $this->teacher->insert('teacher_attendances', $data);
                        }
                    }
                }
                $start_date->modify('+1 day');
            }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['day'] = date('d', strtotime($this->input->post('start_date')));
            $this->data['month'] = date('m', strtotime($this->input->post('start_date')));
            $this->data['year'] = date('Y', strtotime($this->input->post('start_date')));

            $this->data['start_date'] = $this->input->post('start_date');
            $this->data['to_date'] = $this->input->post('to_date');
            create_log('Has been process Teacher Attendance');
        }

        $this->layout->title($this->lang->line('teacher_attendance') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }



    /*****************Function update_single_attendance**********************************
     * @type            : Function
     * @function name   : update_single_attendance
     * @description     : Process to update single teacher attendance status               
     *                        
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function update_single_attendance()
    {

        $status = $this->input->post('status');
        $condition['school_id'] = $this->input->post('school_id');
        $condition['teacher_id'] = $this->input->post('teacher_id');

        $school = $this->teacher->get_school_by_id($condition['school_id']);
        if (!$school->academic_year_id) {
            echo 'ay';
            die();
        }
        $condition['academic_year_id'] = $school->academic_year_id;

        // Set the start and end dates
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('to_date');

        // Convert the dates to DateTime objects
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);

        // Loop through the dates between the start and end dates
        while ($start_date <= $end_date) {
            $condition['month'] = date('m', strtotime($start_date->format('Y-m-d')));
            $condition['year'] = date('Y', strtotime($start_date->format('Y-m-d')));
            $field = 'day_' . abs(date('d', strtotime($start_date->format('Y-m-d'))));

            $this->teacher->update('teacher_attendances', array($field => $status, 'modified_at' => date('Y-m-d H:i:s')), $condition);
            // echo $this->db->last_query();
            // echo "<br>";
            // Move the start date to the next day
            $start_date->modify('+1 day');
        }
        echo TRUE;
    }



    /*****************Function update_all_attendance**********************************
     * @type            : Function
     * @function name   : update_all_attendance
     * @description     : Process to update all teacher attendance status                 
     *                        
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function update_all_attendance()
    {
        $status = $this->input->post('status');
        $condition['school_id'] = $this->input->post('school_id');

        $school = $this->teacher->get_school_by_id($condition['school_id']);
        if (!$school->academic_year_id) {
            echo 'ay';
            die();
        }
        $condition['academic_year_id'] = $school->academic_year_id;

        // Set the start and end dates
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('to_date');

        // Convert the dates to DateTime objects
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);

        // Loop through the dates between the start and end dates
        while ($start_date <= $end_date) {
            $condition['month'] = date('m', strtotime($start_date->format('Y-m-d')));
            $condition['year'] = date('Y', strtotime($start_date->format('Y-m-d')));
            $field = 'day_' . abs(date('d', strtotime($start_date->format('Y-m-d'))));

            $this->teacher->update('teacher_attendances', array($field => $status, 'modified_at' => date('Y-m-d H:i:s')), $condition);
            // echo $this->db->last_query();
            // echo "<br>";
            // Move the start date to the next day
            $start_date->modify('+1 day');
        }
        echo TRUE;
    }
}
