<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Exphead extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Exphead_Model', 'exphead', true);
         
          // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_accounting')){                        
              redirect('dashboard/index');
            }
        }
    }


    public function index($school_id = null) {
        
        check_permission(VIEW);
            
        $this->data['expheads'] = $this->exphead->get_exphead_list($school_id);  
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_expenditure_head'). ' | ' . SMS);
        $this->layout->view('exp_head/index', $this->data);            
       
    }

    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_exphead_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_exphead_data();

                $insert_id = $this->exphead->insert('expenditure_heads', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a expenditure head : '. $data['title']);                    
                    success($this->lang->line('insert_success'));
                    redirect('accounting/exphead/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/exphead/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }
        
        $this->data['expheads'] = $this->exphead->get_exphead_list();  
        $this->data['schools'] = $this->schools;
   
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('exp_head/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/exphead/index');
        }
                
        if ($_POST) {
            $this->_prepare_exphead_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_exphead_data();
                $updated = $this->exphead->update('expenditure_heads', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a expenditure head : '. $data['title']);                    
                    success($this->lang->line('update_success'));
                    redirect('accounting/exphead/index/'.$data['school_id']);     
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/exphead/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['exphead'] = $this->exphead->get_single('expenditure_heads', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['exphead'] = $this->exphead->get_single('expenditure_heads', array('id' => $id));

            if (!$this->data['exphead']) {
                 redirect('accounting/exphead/index');
            }
        }

        $this->data['expheads'] = $this->exphead->get_exphead_list($this->data['exphead']->school_id);  
        $this->data['school_id'] = $this->data['exphead']->school_id;
        $this->data['filter_school_id'] = $this->data['exphead']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('exp_head/index', $this->data);
    }

    private function _prepare_exphead_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('expenditure_head'), 'trim|required|callback_title');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
    }

   public function title()
   {             
      if($this->input->post('id') == '')
      {   
          $exphead = $this->exphead->duplicate_check($this->input->post('school_id'), $this->input->post('title')); 
          if($exphead){
                $this->form_validation->set_message('title',  $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $exphead = $this->exphead->duplicate_check($this->input->post('school_id'),$this->input->post('title'), $this->input->post('id')); 
          if($exphead){
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }

    private function _get_posted_exphead_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'title';
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
            redirect('accounting/exphead/index');
        }
        
        $exphead = $this->exphead->get_single('expenditure_heads', array('id' => $id));
        
        if ($this->exphead->delete('expenditure_heads', array('id' => $id))) { 
            
            create_log('Has been deleted a expenditure head : '. $exphead->title);             
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('accounting/exphead/index/'.$exphead->school_id);
    }

}
