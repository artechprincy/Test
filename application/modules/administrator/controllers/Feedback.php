<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Feedback_Model', 'feedback', true);        
    }

    public function index() {

        check_permission(VIEW);
        
        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_feedback') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }

    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_feedback_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feedback_data();

                $insert_id = $this->feedback->insert('guardian_feedbacks', $data);
                if ($insert_id) {
                    
                    create_log('Has been add feedback');
                    success($this->lang->line('insert_success'));
                    redirect('administrator/feedback/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/feedback/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }


    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('administrator/feedback/index');
        }
        
        if ($_POST) {
            $this->_prepare_feedback_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feedback_data();
                $updated = $this->feedback->update('guardian_feedbacks', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been update feedback');
                    
                    success($this->lang->line('update_success'));
                    redirect('administrator/feedback/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('administrator/feedback/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['feedback'] = $this->feedback->get_single('guardian_feedbacks', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['feedback'] = $this->feedback->get_single('guardian_feedbacks', array('id' => $id));

            if (!$this->data['feedback']) {
                redirect('administrator/feedback/index');
            }
        }

        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }

    public function get_single_feedback(){
        
       $feedback_id = $this->input->post('feedback_id');
       
       $this->data['feedback'] = $this->feedback->get_single_feedback($feedback_id);
       echo $this->load->view('feedback/get-single-feedback', $this->data);
    }

    private function _prepare_feedback_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim');
        $this->form_validation->set_rules('feedback', $this->lang->line('feedback'), 'trim|required');
    }
    private function _get_posted_feedback_data() {

        $items = array();
        $items[] = 'feedback';
        $data = elements($items, $_POST);


        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['guardian_id'] = $this->session->userdata('profile_id');
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        return $data;
    }


    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('administrator/feedback/index');
        }
        
        if ($this->feedback->delete('guardian_feedbacks', array('id' => $id))) {
            
            create_log('Has been deleted feedback');
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
       redirect('administrator/feedback/index');
    }


    public function activate($id = null) {

        check_permission(EDIT);

        if ($id == '') {
            error($this->lang->line('update_failed'));
            redirect('administrator/feedback/index');
        }

        
        $this->feedback->update('guardian_feedbacks', array('is_publish' => 1), array('id' => $id));
     
        create_log('Has been activated a feedback');
        success($this->lang->line('update_success'));
        redirect('administrator/feedback/index');
    }


    public function deactivate($id = null) {

        check_permission(EDIT);

        if ($id == '') {
            error($this->lang->line('update_failed'));
            redirect('administrator/feedback/index');
        }

        $this->feedback->update('guardian_feedbacks', array('is_publish' => 0), array('id' => $id));
     
        create_log('Has been deactivated a feedback');
        success($this->lang->line('update_success'));
        redirect('administrator/feedback/index');
    }
    
    
}