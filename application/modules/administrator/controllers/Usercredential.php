<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usercredential extends MY_Controller {

   public function __construct() {
        parent::__construct();
                
        $this->load->model('Administrator_Model', 'administrator', true);
        $this->data['roles'] = $this->administrator->get_list('roles', array('status' => 1, 'is_super_admin'=>0), '','', '', 'id', 'ASC');
        $this->data['classes'] = $this->administrator->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
    }

    public $data = array();


    public function index(){
        
        
        check_permission(VIEW);
        
        $this->data['users'] = '';
        
        //  if ($_POST) {
             
            $role_id  = $this->input->post('role_id');
            $class_id = $this->input->post('class_id');            
            $user_id  = $this->input->post('user_id');  
            $school_id  = $this->input->post('school_id');            
            if ($this->session->userdata('role_id') != SUPER_ADMIN) {
                $school_id = $this->session->userdata('school_id');
            }

            $this->data['users'] = $this->administrator->get_user_list($school_id, $role_id, $class_id, $user_id);
            $this->data['role_id'] = $role_id;
            $this->data['class_id'] = $class_id;
            $this->data['user_id'] = $user_id;
            $this->data['school_id'] = $school_id;
        //  }         
        
        $this->layout->title($this->lang->line('manage_user_credential'). ' | ' . SMS);
        $this->layout->view('credential/index', $this->data); 
    }

}
