<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admission_no extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        parent::__construct();

        $this->load->model('Admission_Model', 'admission', true);
    }

    public function index()
    {
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $school_id = $this->session->userdata('school_id');
        }

        $this->data['admission_no'] = $this->db->get_where('schools', ['id' => $school_id])->row()->admission_no;
        $this->layout->title($this->lang->line('admission_no') . ' | ' . SMS);
        $this->layout->view('admission/index', $this->data);
    }

    public function add()
    {
        if ($_POST) {
            if ($this->session->userdata('role_id') != SUPER_ADMIN) {
                $school_id = $this->session->userdata('school_id');
            }
            $this->_prepare_admission_validation();
            if ($this->form_validation->run() === TRUE) {
                if ($this->admission->check_admission_no($this->input->post('admission_no'))) {
                    $this->form_validation->set_message('admission_no', "This admission no is already used.");
                    return FALSE;
                }
                $update = $this->db->where('id', $school_id)->update('schools',['admission_no' => $this->input->post('admission_no')]);
                // echo $this->db->last_query();die;
                if ($update) {
                    create_log('Has been created a Admission No : ' . $this->input->post('admission_no'));

                    success($this->lang->line('insert_success'));
                    redirect('/dashboard/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/admission_no/index');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('admission_no/index', $this->data);
    }

    private function _prepare_admission_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required');
    }
}
