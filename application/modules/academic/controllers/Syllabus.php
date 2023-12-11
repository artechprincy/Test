<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syllabus extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        $this->load->model('Syllabus_Model', 'syllabus', true);  
    }

    public function index($class_id = null) {
        
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        // for super admin 
        $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['years'] = $this->syllabus->get_year_list($school_id);
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id, $school_id);            
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_syllabus'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);            
       
    }

    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_syllabus_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_syllabus_data();

                $insert_id = $this->syllabus->insert('syllabuses', $data);
                if ($insert_id) {
                    
                       
                    $class = $this->syllabus->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been added syllabus : '. $data['title'].' for class : '. $class->name);
                    
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/syllabus/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/syllabus/add/'.$data['class_id']);
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id);            

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['years'] = $this->syllabus->get_year_list($_SESSION['school_id']);
            $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_syllabus_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_syllabus_data();
                $updated = $this->syllabus->update('syllabuses', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->syllabus->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated syllabus : '. $data['title'].' for class : '. $class->name);                    
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/syllabus/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/syllabus/edit/' . $this->input->post('id'));
                }
            } else {
                 error($this->lang->line('update_failed'));
                 $this->data['syllabus'] = $this->syllabus->get_single('syllabuses', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['syllabus'] = $this->syllabus->get_single('syllabuses', array('id' => $id));

            if (!$this->data['syllabus']) {
               redirect('academic/syllabus/index');
            }
        }
        
        $class_id = $this->data['syllabus']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id, $this->data['syllabus']->school_id);            
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['years'] = $this->syllabus->get_year_list($_SESSION['school_id']);
            $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['syllabus']->school_id;
        $this->data['filter_school_id'] = $this->data['syllabus']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
        
    }

    public function view( $syllabus_id = null ){
        check_permission(VIEW);
        
        if(!is_numeric($syllabus_id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        $this->data['syllabus'] = $this->syllabus->get_single_syllabus( $syllabus_id);
        $class_id = $this->data['syllabus']->class_id;
        
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id);    

         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
        }
        $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
    }

    public function get_single_syllabus(){
        
       $syllabus_id = $this->input->post('syllabus_id');
       
       $this->data['syllabus'] = $this->syllabus->get_single_syllabus( $syllabus_id);
       echo $this->load->view('syllabus/get-single-syllabus', $this->data);
    }

    private function _prepare_syllabus_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        
        $this->form_validation->set_rules('academic_year_id', $this->lang->line('academic_year'), 'trim|required');   
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');   
        $this->form_validation->set_rules('title',  $this->lang->line('title'), 'trim|required');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
        $this->form_validation->set_rules('syllabus', $this->lang->line('syllabus'), 'trim|callback_syllabus');   
    }

   public function syllabus()
   {  
     
        if($_FILES['syllabus']['name']){
            $name = $_FILES['syllabus']['name'];            
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
                return TRUE;
            } else {
                $this->form_validation->set_message('syllabus', $this->lang->line('select_valid_file_format'));         
                return FALSE; 
            }
        }   
       
   }

    private function _get_posted_syllabus_data() {

        $items = array();
        $items[] = 'academic_year_id';
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'subject_id';
        $items[] = 'title';
        $items[] = 'note';
        
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            
            $school = $this->syllabus->get_school_by_id($data['school_id']);
            
            // if(!$school->academic_year_id){
            //     error($this->lang->line('set_academic_year_for_school'));
            //     redirect('academic/syllabus/index');
            // }
            
            // $data['academic_year_id'] = $school->academic_year_id;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
        }
        
        
        if(!empty($_FILES)){  
           $data['syllabus'] = $this->_upload_syllabus();
        }
        
        return $data;
    }

    private function _upload_syllabus(){
           
        $prev_syllabus     = $this->input->post('prev_syllabus');
        $syllabus          = $_FILES['syllabus']['name'];
        $syllabus_type     = $_FILES['syllabus']['type'];
        $return_syllabus   = '';

        if ($syllabus != "") {
            if ($syllabus_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || 
                    $syllabus_type == 'application/msword' || $syllabus_type == 'text/plain' ||
                    $syllabus_type == 'application/vnd.ms-office' || $syllabus_type == 'application/pdf') {

                $destination = 'assets/uploads/syllabus/';               

                $file_type  = explode(".", $syllabus);
                $extension  = strtolower($file_type[count($file_type) - 1]);
                $syllabus_path = 'syllabus-'.time() . '-gsms.' . $extension;

                move_uploaded_file($_FILES['syllabus']['tmp_name'], $destination . $syllabus_path);

                // need to unlink previous syllabus
                if ($prev_syllabus != "") {
                    if (file_exists($destination . $prev_syllabus)) {
                        @unlink($destination . $prev_syllabus);
                    }
                }

                $return_syllabus = $syllabus_path;
            }
        } else {
            $return_syllabus = $prev_syllabus;
        }

        return $return_syllabus;
    }

    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        $syllabus = $this->syllabus->get_single('syllabuses', array('id' => $id));
        if ($this->syllabus->delete('syllabuses', array('id' => $id))) {   
            
               
            $class = $this->syllabus->get_single('classes', array('id' => $syllabus->class_id, 'school_id'=>$syllabus->school_id));
            create_log('Has been deleted a syllabus : '. $syllabus->title.' for class:'. $class->name);
            
            
            // delete syllabus file
            $destination = 'assets/uploads/';
            if (file_exists( $destination.'/syllabus/'.$syllabus->syllabus)) {
                @unlink($destination.'/syllabus/'.$syllabus->syllabus);
            }
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/syllabus/index/'.$syllabus->class_id);
    }
    
    
    

}
