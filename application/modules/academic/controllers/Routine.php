<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Routine extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();    
        $this->load->model('Routine_Model', 'routine', true);            
        
    }

    public function index($class_id = null) {
        
        check_permission(VIEW);
        
        
        if(logged_in_role_id() == STUDENT){
            $class_id = $this->session->userdata('class_id');
            $section_id = $this->session->userdata('section_id');
        }
        
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/routine/index');
        }
        
        $this->data['class_id'] = $class_id;
         
        
        // for super admin 
        $school_id = '';        
        if($_POST){
            
            $school_id               = $this->input->post('school_id');
            $this->data['filter_school_id'] = $school_id;
            $class_id                = $this->input->post('class_id');;
            $this->data['filter_class_id']  = $class_id;            
            $this->data['school'] = $this->routine->get_school_by_id($school_id);
        }else{
           $class = $this->routine->get_single('classes', array('id' => $class_id));  
           $school_id = @$class->school_id;
        }
        
        $this->data['time_tables'] = $this->routine->get_time_table_list($school_id);
        $this->data['sections'] = $this->routine->get_section_list($class_id, $school_id); 
        $this->data['single_class'] = $this->routine->get_single('classes', array('id' => $class_id));      
        
        $condition = array();
        
        $condition['status'] = 1;  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
                        
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->routine->get_list('teachers', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $school_id = $condition['school_id'];
        }
        
        if($school_id){
            $condition['school_id'] = $school_id; 
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school'] = $this->routine->get_school_by_id($school_id);
        
        $this->data['years'] = $this->routine->get_year_list($school_id);
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_routine'). ' | ' . SMS);
         if($this->session->userdata('role_id') == TEACHER){ 
            $this->layout->view('routine/teacher', $this->data); 
        }else{
            $this->layout->view('routine/index', $this->data);             
        }      
    }

    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_routine_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_routine_data();

                $insert_id = $this->routine->insert('time_table', $data);
                if ($insert_id) {
                    
                    $class = $this->routine->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been created a routine for class : '. $class->name);
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/routine/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/routine/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }
        
        $school_id = '';
        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
          $school_id = $this->input->post('school_id');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['time_tables'] = $this->routine->get_time_table_list($school_id);
        $this->data['sections'] = $this->routine->get_section_list($class_id); 
        $this->data['single_class'] = $this->routine->get_single('classes', array('id' => $class_id)); 
                
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->routine->get_list('teachers', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $school_id = $condition['school_id'];
        }
        
        $this->data['years'] = $this->routine->get_year_list($school_id);
        $this->data['school'] = $this->routine->get_school_by_id($school_id);
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('routine/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/routine/index');     
        }
        
        if ($_POST) {
            $this->_prepare_routine_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_routine_data();
                $updated = $this->routine->update('time_table', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->routine->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated a routine for class : '. $class->name);
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/routine/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/routine/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['routine'] = $this->routine->get_single('routines', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['routine'] = $this->routine->get_single('time_table', array('id' => $id));
            if (!$this->data['routine']) {
                 redirect('academic/routine/index');
            }
        }
        
        $class_id = $this->data['routine']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
      
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['sections'] = $this->routine->get_section_list($class_id);        
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['teachers'] = $this->routine->get_list('teachers', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] = $this->routine->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['routine']->school_id;
        $this->data['filter_school_id'] = $this->data['routine']->school_id;
        $this->data['school'] = $this->routine->get_school_by_id($this->data['routine']->school_id); 
        $this->data['single_class'] = $this->routine->get_single('classes', array('id'=>$this->data['routine']->class_id));

        $this->data['time_tables'] = $this->routine->get_time_table_list($this->data['school_id']);
        $this->data['years'] = $this->routine->get_year_list($this->data['school_id']);
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('routine/index', $this->data);
    }

    private function _prepare_routine_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('academic_year_id', $this->lang->line('academic_year'), 'trim|required'); 
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');    
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');   
        $this->form_validation->set_rules('start_date', $this->lang->line('start_date'), 'trim|required');   
        $this->form_validation->set_rules('end_date', $this->lang->line('end_date'), 'trim|required');   
        $this->form_validation->set_rules('start_time', $this->lang->line('start_time'), 'trim|required');   
        $this->form_validation->set_rules('end_time', $this->lang->line('end_time'), 'trim|required');   
        // $this->form_validation->set_rules('room_no', $this->lang->line('room_no'), 'trim|required|callback_room_no');
    }

   public function subject_id()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'section_id'=> $this->input->post('section_id'),
              'subject_id'=> $this->input->post('subject_id'),
              'day'=> $this->input->post('day')
          );
          $routine = $this->routine->duplicate_routine($condition);          
          if($routine){
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'section_id'=> $this->input->post('section_id'),
              'subject_id'=> $this->input->post('subject_id'),
              'day'=> $this->input->post('day')
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }

   public function teacher_id()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'teacher_id'=> $this->input->post('teacher_id'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time'),
          );
          $routine = $this->routine->duplicate_routine($condition); 
          if($routine){
                $this->form_validation->set_message('teacher_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'teacher_id'=> $this->input->post('teacher_id'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time')             
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('teacher_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }

   public function room_no()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'room_no'=> $this->input->post('room_no'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time'),
          );
          $routine = $this->routine->duplicate_routine($condition); 
          if($routine){
                $this->form_validation->set_message('room_no', $this->lang->line('this_room_already_allocated'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'school_id'=> $this->input->post('school_id'),
              'room_no'=> $this->input->post('room_no'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time')            
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('room_no', $this->lang->line('this_room_already_allocated'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }

    private function _get_posted_routine_data() {

        $items = array();
        $items[] = 'academic_year_id';
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'start_date';
        $items[] = 'end_date';
        $items[] = 'start_time';
        $items[] = 'end_time';
        // $items[] = 'room_no';
        
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            
            $school = $this->routine->get_school_by_id($data['school_id']);        
            // if(!$school->academic_year_id){
            //     error($this->lang->line('set_academic_year_for_school'));
            //     redirect('academic/routine/index'); 
            // }        
            // $data['academic_year_id'] = $school->academic_year_id;
            
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
            redirect('academic/routine/index');  
        }
        
        $routine = $this->routine->get_single('time_table', array('id' => $id));
        
        if ($this->routine->delete('time_table', array('id' => $id))) { 
            
            $class = $this->routine->get_single('classes', array('id' => $routine->class_id, 'school_id'=>$routine->school_id));
            create_log('Has been delete a routine for class : '. $class->name);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/routine/index/'.$routine->class_id);
    }  

}
