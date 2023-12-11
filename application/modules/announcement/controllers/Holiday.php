<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Holiday_Model', 'holiday', true);
            
    }

    public function index($school_id = null, $id = null) {
        
         check_permission(VIEW);
      
        $this->data['holidays'] = $this->holiday->get_holiday_list($school_id); 
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title( $this->lang->line('manage_holiday'). ' | ' . SMS);
        $this->layout->view('holiday/index', $this->data);            
       
    }

    public function add() {
        
         check_permission(ADD);

        if ($_POST) {
            $this->_prepare_holiday_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_holiday_data();

                $insert_id = $this->holiday->insert('holidays', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a holiday : '.$data['title']); 
                    
                    success($this->lang->line('insert_success'));
                    redirect('announcement/holiday/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('announcement/holiday/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['holidays'] = $this->holiday->get_holiday_list(); 
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('holiday/index', $this->data);
    }


    public function edit($id = null) {       
       
         check_permission(EDIT);
        
         if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
           redirect('announcement/holiday/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_holiday_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_holiday_data();
                $updated = $this->holiday->update('holidays', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                     create_log('Has been updated a holiday : '.$data['title']);  
                     
                    success($this->lang->line('update_success'));
                    redirect('announcement/holiday/index/'.$data['school_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('announcement/holiday/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['holiday'] = $this->holiday->get_single('holidays', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['holiday'] = $this->holiday->get_single('holidays', array('id' => $id));

            if (!$this->data['holiday']) {
                 redirect('announcement/holiday/index');
            }
        }

        $this->data['holidays'] = $this->holiday->get_holiday_list($this->data['holiday']->school_id);
        $this->data['school_id'] = $this->data['holiday']->school_id;
        $this->data['filter_school_id'] = $this->data['holiday']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('holiday/index', $this->data);
    }

    public function view($id = null){
        
         check_permission(VIEW);
         
         if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
           redirect('announcement/holiday/index'); 
        }
        
        $this->data['holidays'] = $this->holiday->get_holiday_list();
        
        $this->data['holiday'] = $this->holiday->get_single('holidays', array('id' => $id));
        
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('holiday'). ' | ' . SMS);
        $this->layout->view('holiday/index', $this->data);
    }


    public function get_single_holiday(){
        
       $holiday_id = $this->input->post('holiday_id');
       
       $this->data['holiday'] = $this->holiday->get_single_holiday($holiday_id);
       echo $this->load->view('holiday/get-single-holiday', $this->data);
    }
    

    private function _prepare_holiday_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|callback_title');   
        $this->form_validation->set_rules('date_from', $this->lang->line('from_date'), 'trim|required');   
        $this->form_validation->set_rules('date_to', $this->lang->line('to_date'), 'trim|required|callback_date_to');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim|required');   
    }

   public function title()
   {             
      if($this->input->post('id') == '')
      {   
          $holiday = $this->holiday->duplicate_check($this->input->post('school_id'), $this->input->post('title'), $this->input->post('date_from')); 
          if($holiday){
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $holiday = $this->holiday->duplicate_check($this->input->post('school_id'), $this->input->post('title'),$this->input->post('date_from'), $this->input->post('id')); 
          if($holiday){
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }

   public function date_to()
   {             
      
        $date_from = strtotime($this->input->post('date_from'));
        $date_to   = strtotime($this->input->post('date_to'));
          
          if($date_to < $date_from){
                $this->form_validation->set_message('date_to', $this->lang->line('to_date_must_be_big'));         
                return FALSE;
          } else {
              return TRUE;
          }
        
   }


    private function _get_posted_holiday_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'title';
        $items[] = 'date_from';    
        $items[] = 'note';
        $items[] = 'is_view_on_web';
        $data = elements($items, $_POST);  
      
        $data['date_from'] = date('Y-m-d', strtotime($this->input->post('date_from')));
        $data['date_to'] = date('Y-m-d', strtotime($this->input->post('date_to')));
        
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
           redirect('announcement/holiday/index'); 
        }
        
        $holiday = $this->holiday->get_single('holidays', array('id' => $id));
        
        if ($this->holiday->delete('holidays', array('id' => $id))) {               
            
            create_log('Has been deleted a holiday : '.$holiday->title);  
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('announcement/holiday/index/'.$holiday->school_id);
    }

}
