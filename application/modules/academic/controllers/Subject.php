<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subject extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
  
         $this->load->model('Subject_Model', 'subject', true);         
    }

    public function index($class_id = null) {
        
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/subject/index');    
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
        $this->data['subjects'] = $this->subject->get_subject_list($class_id, $school_id);        
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['departments'] = $this->subject->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['classes'] = $this->subject->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->subject->get_list('teachers', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['list'] = TRUE;
        $this->data['schools'] = $this->schools;
        $this->layout->title($this->lang->line('manage_subject'). ' | ' . SMS);
        $this->layout->view('subject/index', $this->data); 
    }

    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_subject_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_subject_data();

                $insert_id = $this->subject->insert('subjects', $data);
                if ($insert_id) {
                    
                    $class = $this->subject->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been added a sucject : '. $data['name'].' for class : '. $class->name);
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/subject/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/subject/add/'.$data['class_id']);
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
        $this->data['subjects'] = $this->subject->get_subject_list($class_id);

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['departments'] = $this->subject->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['subjects'] = $this->subject->get_subject_list($class_id, $_SESSION['school_id']);
            $this->data['classes'] = $this->subject->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->subject->get_list('teachers', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('subject/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/subject/index');     
        }
        
        if ($_POST) {
            $this->_prepare_subject_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_subject_data();
                $updated = $this->subject->update('subjects', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->subject->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated a sucject : '. $data['name'].' for class : '. $class->name);
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/subject/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('updtae_failed'));
                    redirect('academic/subject/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('updtae_failed'));
                $this->data['subject'] = $this->subject->get_single('subjects', array('id' =>  $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['subject'] = $this->subject->get_single('subjects', array('id' => $id));

            if (!$this->data['subject']) {
                 redirect('academic/subject/index');      
            }
        }
        
        
        $class_id = $this->data['subject']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['subjects'] = $this->subject->get_subject_list($class_id, $this->data['subject']->school_id);        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
            $this->data['departments'] = $this->subject->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['subjects'] = $this->subject->get_subject_list($class_id, $_SESSION['school_id']);
            $this->data['classes'] = $this->subject->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->subject->get_list('teachers', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['edit'] = TRUE; 
        $this->data['school_id'] = $this->data['subject']->school_id;
        $this->data['filter_school_id'] = $this->data['subject']->school_id;
        
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('subject/index', $this->data);
    }
    
    

    public function view($subject_id = null){
        
        check_permission(VIEW);
        
        if(!is_numeric($subject_id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/subject/index');    
        }
        
        $this->data['subject'] = $this->subject->get_single_subject($subject_id);
        $class_id = $this->data['subject']->class_id;
        
        $this->data['subjects'] = $this->subject->get_subject_list($class_id);        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $_SESSION['school_id'];
        }
        $this->data['classes'] = $this->subject->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['teachers'] = $this->subject->get_list('teachers', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        $this->data['schools'] = $this->schools; 
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' | ' . SMS);
        $this->layout->view('subject/index', $this->data);
    }

    public function get_single_subject(){
        
       $subject_id = $this->input->post('subject_id');
       
       $this->data['subject'] = $this->subject->get_single_subject($subject_id);
       echo $this->load->view('subject/get-single-subject', $this->data);
    }

    private function _prepare_subject_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('teacher_id', $this->lang->line('teacher'), 'trim|required');   
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim');   
        $this->form_validation->set_rules('code', $this->lang->line('subject_code'), 'trim');   
        $this->form_validation->set_rules('author', $this->lang->line('author'), 'trim');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required|trim|callback_name');
    }

   public function name()
   {             
      if($this->input->post('id') == '')
      {   
          $subject = $this->subject->duplicate_check($this->input->post('school_id'), $this->input->post('class_id'),$this->input->post('name')); 
          if($subject){
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $subject = $this->subject->duplicate_check($this->input->post('school_id'), $this->input->post('class_id'),$this->input->post('name'), $this->input->post('id')); 
          if($subject){
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }else{
          return TRUE;
      }      
   }

    private function _get_posted_subject_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'teacher_id';
        $items[] = 'type';
        $items[] = 'code';
        $items[] = 'department_id';
        $items[] = 'author';
        $items[] = 'name';
        $items[] = 'note';
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();                       
        }

        return $data;
    }

    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/subject/index');    
        }
        
        $subject = $this->subject->get_single('subjects', array('id' => $id));
        
        if ($this->subject->delete('subjects', array('id' => $id))) { 
            
            $class = $this->subject->get_single('classes', array('id' => $subject->class_id, 'school_id'=>$subject->school_id));
            create_log('Has been deleted a sucject : '. $subject->name.' for class : '. $class->name);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/subject/index/'.$subject->class_id);
        
    }
}
