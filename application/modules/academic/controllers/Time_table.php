<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Time_table extends MY_Controller
{

    public $data = array();


    function __construct()
    {
        parent::__construct();
        $this->load->model('Routine_Model', 'routine', true);
    }

    public function index($time_table_id = NULL)
    {
        $this->data['time_table'] = $this->routine->get_single('time_table', array('id' => $time_table_id));
        $this->data['routines'] = $this->routine->get_routines_list($time_table_id);
        // echo "<pre>";
        // print_r($this->data['time_table']);die;
        $condition = array();

        $condition['status'] = 1;

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {

            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
            $this->data['teachers'] = $this->routine->get_list('teachers', $condition, '', '', '', 'id', 'ASC');
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
            $school_id = $condition['school_id'];
        }

        if (isset($this->data['time_table']->school_id)) {
            $condition['school_id'] = $this->data['time_table']->school_id;
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }
        $this->data['schools'] = $this->schools;

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_routine') . ' | ' . SMS);
        if ($this->session->userdata('role_id') == TEACHER) {
            $this->layout->view('timetable/teacher', $this->data);
        } else {
            $this->layout->view('timetable/index', $this->data);
        }
    }

    public function add()
    {
        if ($_POST) {
            $this->_prepare_routine_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_routine_data();
                $insert_id = $this->db->insert_batch('routines', $data);
                if ($insert_id) {
                    create_log('Has been created a routine for class : ');

                    success($this->lang->line('insert_success'));
                    redirect('academic/time_table/index/' . $_POST['time_table_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/time_table/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['time_table'] = $this->routine->get_single('time_table', array('id' => $_POST['time_table_id']));

        $condition = array();

        $condition['status'] = 1;

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {

            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
            $this->data['teachers'] = $this->routine->get_list('teachers', $condition, '', '', '', 'id', 'ASC');
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
            $school_id = $condition['school_id'];
        }

        if ($school_id) {
            $condition['school_id'] = $school_id;
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('timetable/index', $this->data);
    }

    private function _prepare_routine_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('time_table_id', $this->lang->line('time_table'), 'trim|required');
        $this->form_validation->set_rules('start_time', $this->lang->line('start_time'), 'trim|required|callback_start_time');
        $this->form_validation->set_rules('end_time', $this->lang->line('end_time'), 'trim|required|callback_end_time');
        // $this->form_validation->set_rules('teacher_id', $this->lang->line('teacher'), 'trim|callback_teacher_id');
    }

    private function _get_posted_routine_data()
    {

        $items = array();
        $items[] = 'time_table_id';
        $items[] = 'is_break';
        $items[] = 'subject_id';
        $items[] = 'teacher_id';
        $items[] = 'start_time';
        $items[] = 'end_time';

        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }
        $rows = [];
        foreach ($_POST['day'] as $day) {
            $row = $data;
            $row['day'] = $day;
            $rows[] = $row;
        }
        return $rows;
    }

    public function start_time()
    {
        if (strtotime($this->input->post('start_time')) >= strtotime($this->input->post('end_time'))) {
            $this->form_validation->set_message('start_time', "Please end time grater then start time.");
            return FALSE;
        } else {
            if ($this->input->post('id') == '') {
                $routine = $this->routine->checkTimeTable($this->input->post('time_table_id'), $this->input->post('start_time'));
                if ($routine) {
                    $this->form_validation->set_message('start_time', "Please Select Start Time school time");
                    return FALSE;
                } else {
                    $check = $this->routine->checkBeetween($this->input->post('time_table_id'), $this->input->post('day'), $this->input->post('start_time'));
                    if ($check) {
                        $this->form_validation->set_message('start_time', "This Time is busy");
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            } else if ($this->input->post('id') != '') {
                $routine = $this->routine->checkTimeTable($this->input->post('time_table_id'), $this->input->post('start_time'), $this->input->post('id'));
                if ($routine) {
                    $this->form_validation->set_message('start_time', "Please Select Start Time school time");
                    return FALSE;
                } else {
                    $check = $this->routine->checkBeetween($this->input->post('time_table_id'), $this->input->post('day'), $this->input->post('start_time'), $this->input->post('id'));
                    if ($check) {
                        $this->form_validation->set_message('start_time', "This Time is busy");
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            } else {
                return TRUE;
            }
        }
    }

    public function end_time()
    {
        if ($this->input->post('id') == '') {
            if (strtotime($this->input->post('start_time')) >= strtotime($this->input->post('end_time'))) {
                $this->form_validation->set_message('end_time', "Please end time grater then start time.");
                return FALSE;
            } else {
                $routine = $this->routine->checkTimeTable($this->input->post('time_table_id'), $this->input->post('end_time'));
                if ($routine) {
                    $this->form_validation->set_message('end_time', "Please Select End Time school time");
                    return FALSE;
                } else {
                    $check = $this->routine->checkBeetween($this->input->post('time_table_id'), $this->input->post('day'), $this->input->post('end_time'));
                    if ($check) {
                        $this->form_validation->set_message('end_time', "This Time is busy");
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            }
        } else if ($this->input->post('id') != '') {
            if (strtotime($this->input->post('start_time')) >= strtotime($this->input->post('end_time'))) {
                $this->form_validation->set_message('end_time', "Please end time grater then start time.");
                return FALSE;
            } else {
                $routine = $this->routine->checkTimeTable($this->input->post('time_table_id'), $this->input->post('end_time'), $this->input->post('id'));
                if ($routine) {
                    $this->form_validation->set_message('end_time', "Please Select End Time school time");
                    return FALSE;
                } else {
                    $check = $this->routine->checkBeetween($this->input->post('time_table_id'), $this->input->post('day'), $this->input->post('end_time'), $this->input->post('id'));
                    if ($check) {
                        $this->form_validation->set_message('end_time', "his Time is busy");
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            }
        } else {
            return TRUE;
        }
    }
}
