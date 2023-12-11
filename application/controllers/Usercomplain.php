<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usercomplain extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Complain_Model', 'complain', true);  
        
        if($this->session->userdata('role_id') == SUPER_ADMIN){ 
            error($this->lang->line('permission_denied'));
            redirect('dashboard/index');
        }
    }

    public function index() {

        check_permission(VIEW);
                         
        $this->data['complains'] = $this->complain->get_complain_list();
        $condition['status'] = 1;       
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $condition['school_id']  = $this->session->userdata('school_id');           
        }
        $this->data['complain_types'] = $this->complain->get_list('complain_types', $condition, '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_complain'). ' | ' . SMS);
        $this->layout->view('profile/complain', $this->data);
        
    }

    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_complain_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_complain_data();

                $insert_id = $this->complain->insert('complains', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a complain');                     
                    success($this->lang->line('insert_success'));
                    redirect('usercomplain/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('usercomplain/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }
            
        $condition['status'] = 1;       
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $condition['school_id']  = $this->session->userdata('school_id');
        }
        
        $this->data['complain_types'] = $this->complain->get_list('complain_types', $condition, '','', '', 'id', 'ASC');
        $this->data['complains'] = $this->complain->get_complain_list();  
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('profile/complain', $this->data);
    }

    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('usercomplain/index');
        }
        
        if ($_POST) {
            $this->_prepare_complain_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_complain_data();
                $updated = $this->complain->update('complains', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a complain');                    
                    success($this->lang->line('update_success'));
                    redirect('usercomplain/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('usercomplain/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['complain'] = $this->complain->get_single_complain('complains', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            
            $this->data['complain'] = $this->complain->get_single_complain($id);

            if (!$this->data['complain']) {
                redirect('usercomplain/index');
            }
        }
        
     
        $condition['status'] = 1;       
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $condition['school_id']  = $this->session->userdata('school_id');
        }        
        $this->data['complain_types'] = $this->complain->get_list('complain_types', $condition, '','', '', 'id', 'ASC');
        $this->data['complains'] = $this->complain->get_complain_list();  
        
      
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('profile/complain', $this->data);
    }

    public function get_single_complain(){
        
       $complain_id = $this->input->post('complain_id');
       
       $this->data['complain'] = $this->complain->get_single_complain($complain_id);     
       echo $this->load->view('profile/get-single-complain', $this->data);
    }

    private function _prepare_complain_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('type_id', $this->lang->line('complain_type'), 'trim|required');
        $this->form_validation->set_rules('complain_date', $this->lang->line('date'), 'trim|required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');
        
    }

    private function _get_posted_complain_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'type_id';
        $items[] = 'description';

        $data = elements($items, $_POST);

        $data['complain_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('complain_date')));
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
        } else {
            
            $data['user_id']  = logged_in_user_id();
            $data['role_id']  = logged_in_role_id();
            $data['class_id'] = $this->session->userdata('class_id');
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
            $school = $this->complain->get_school_by_id($data['school_id']);
            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('usercomplain/index');
            }            
            $data['academic_year_id'] = $school->academic_year_id;
            
        }


        return $data;
    }

    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('usercomplain/index');
        }
        
        $complain = $this->complain->get_single('complains', array('id' => $id));
        
        if ($this->complain->delete('complains', array('id' => $id))) {
            
            create_log('Has been deleted a complain : '.$complain->title);

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('usercomplain/index');
    }

}
