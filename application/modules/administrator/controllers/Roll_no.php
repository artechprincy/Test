<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roll_no extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        parent::__construct();

        $this->load->model('Roll_Model', 'roll_no', true);
    }


    public function index($class_id = null)
    {
        if (isset($class_id) && !is_numeric($class_id)) {
            error($this->lang->line('unexpected_error'));
            redirect('dashboard/index');
        }

        // for super admin 
        $school_id = '';
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');
        }

        $school = $this->roll_no->get_school_by_id($school_id);
        $this->data['rolls'] = $this->roll_no->get_roll_list($class_id, $school_id);

        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->roll_no->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['class_list'] = $this->roll_no->get_list('classes', $condition, '', '', '', 'id', 'ASC');

        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_roll') . ' | ' . SMS);
        $this->layout->view('roll/index', $this->data);
    }

    public function add()
    {
        if ($_POST) {
            $this->_prepare_roll_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_roll_data();

                $insert_id = $this->roll_no->insert('student_roll', $data);
                if ($insert_id) {

                    create_log('Has been created an Roll : ' . $data['start_roll']);

                    success($this->lang->line('insert_success'));
                    redirect('administrator/roll_no/index/' . $data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/roll_no/add/' . $data['class_id']);
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $class_id = $this->uri->segment(4);
        if (!$class_id) {
            $class_id = $this->input->post('class_id');
        }

        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->roll_no->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }
        $this->data['class_list'] = $this->roll_no->get_list('classes', $condition, '', '', '', 'id', 'ASC');

        $this->data['class_id'] = $class_id;
        $this->data['schools'] = $this->schools;
        $this->data['rolls'] = $this->roll_no->get_roll_list($class_id, $this->session->userdata('school_id'));

        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('roll/index', $this->data);
    }

    public function edit($id = null)
    {
        if ($_POST) {
            $this->_prepare_roll_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_roll_data();
                $updated = $this->roll_no->update('student_roll', $data, array('id' => $this->input->post('id')));

                if ($updated) {

                    create_log('Has been created an Roll : ' . $data['start_roll']);
                    success($this->lang->line('update_success'));
                    redirect('administrator/roll_no/index/' . $data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/roll_no/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['post'] = $_POST;
            }
        }

        if ($id) {
            $this->data['roll'] = $this->roll_no->get_single('student_roll', array('id' => $id));
            if (!$this->data['roll']) {
                redirect('administrator/roll_no/index');
            }
        }

        $class_id = $this->uri->segment(4);
        if (!$class_id) {
            $class_id = $this->input->post('class_id');
        }
        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->roll_no->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['rolls'] = $this->roll_no->get_roll_list($class_id, $this->session->userdata('school_id'));
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('roll/index', $this->data);
    }

    private function _prepare_roll_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');
        $this->form_validation->set_rules('start_roll', 'start_roll', 'trim|required|callback_start_roll');
    }

    private function _get_posted_roll_data()
    {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'start_roll';

        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    public function start_roll()
    {
        if ($this->input->post('id') == '') {
            if ($this->roll_no->check_roll_no($this->input->post('school_id'), $this->input->post('class_id'), $this->input->post('section_id'))) {
                $this->form_validation->set_message('start_roll', "This Class and section Roll no is already Created.");
                return FALSE;
            } else {
                $start_roll = $this->roll_no->duplicate_check($this->input->post('start_roll'));
                if ($start_roll) {
                    $this->form_validation->set_message('start_roll', 'This Roll no is already used. Please try another one');
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        } else if ($this->input->post('id') != '') {
            if ($this->roll_no->check_roll_no($this->input->post('school_id'), $this->input->post('class_id'), $this->input->post('section_id'), $this->input->post('id'))) {
                $this->form_validation->set_message('start_roll', "This Class and section Roll no is already Created.");
                return FALSE;
            } else {
                $roll_no = $this->roll_no->duplicate_check($this->input->post('start_roll'), $this->input->post('id'));
                if ($roll_no) {
                    $this->form_validation->set_message('start_roll', 'This Roll no is already used. Please try another one');
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
