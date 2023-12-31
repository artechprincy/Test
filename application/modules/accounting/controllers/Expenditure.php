<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenditure extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Expenditure_Model', 'expenditure', true);  
         
        // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_accounting')){                        
              redirect('dashboard/index');
            }
        }
    }

    public function index($school_id = null) {
        
        check_permission(VIEW);
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['expenditure_heads'] = $this->expenditure->get_list('expenditure_heads', $condition);  
            $this->data['payments'] = get_payment_methods($condition['school_id']);
        }       
        
       
        $this->data['expenditures'] = $this->expenditure->get_expenditure_list($school_id); 
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title( $this->lang->line('manage_expenditure'). ' | ' . SMS);
        $this->layout->view('expenditure/index', $this->data);            
       
    }

    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_expenditure_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_expenditure_data();

                $insert_id = $this->expenditure->insert('expenditures', $data);
                if ($insert_id) {
                    
                    create_log('Has been added expenditure : '.$data['amount']);                    
                    success($this->lang->line('insert_success'));
                    redirect('accounting/expenditure/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/expenditure/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['expenditure_heads'] = $this->expenditure->get_list('expenditure_heads', $condition);  
            $this->data['payments'] = get_payment_methods($condition['school_id']);
        }
        $this->data['expenditures'] = $this->expenditure->get_expenditure_list(); 
        $this->data['schools'] = $this->schools;
         
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('expenditure/index', $this->data);
    }

    public function edit($id = null) {  
        
       check_permission(EDIT);
       
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/expenditure/index');
        }
        
        if ($_POST) {
            $this->_prepare_expenditure_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_expenditure_data();
                $updated = $this->expenditure->update('expenditures', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated expenditure : '.$data['amount']);                    
                    success($this->lang->line('update_success'));
                    redirect('accounting/expenditure/index/'.$data['school_id']);     
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/expenditure/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['expenditure'] = $this->expenditure->get_single('expenditures', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['expenditure'] = $this->expenditure->get_single('expenditures', array('id' => $id));

            if (!$this->data['expenditure']) {
                 redirect('accounting/expenditure/index');
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['expenditure_heads'] = $this->expenditure->get_list('expenditure_heads', $condition);    
            $this->data['payments'] = get_payment_methods($condition['school_id']);
        }
        
        $this->data['expenditures'] = $this->expenditure->get_expenditure_list($this->data['expenditure']->school_id);
        $this->data['school_id'] = $this->data['expenditure']->school_id;
        $this->data['filter_school_id'] = $this->data['expenditure']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('expenditure/index', $this->data);
    }

    public function view($id = null){
        
        check_permission(VIEW);
        
         
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/expenditure/index');
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['expenditure_heads'] = $this->expenditure->get_list('expenditure_heads', $condition);        
        }
        
        $this->data['expenditures'] = $this->expenditure->get_expenditure_list();  
        $this->data['expenditure'] = $this->expenditure->get_single_expenditure($id);
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' | ' . SMS);
        $this->layout->view('expenditure/index', $this->data); 
    }

    public function get_single_expenditure(){
        
       $expenditure_id = $this->input->post('expenditure_id');       
       $this->data['expenditure'] = $this->expenditure->get_single_expenditure($expenditure_id);
       echo $this->load->view('expenditure/get-single-expenditure', $this->data);
    }

    private function _prepare_expenditure_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('expenditure_head_id', $this->lang->line('expenditure_head'), 'trim|required');   
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|numeric');   
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required');   
        $this->form_validation->set_rules('expenditure_via', $this->lang->line('expenditure_method'), 'trim|required');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
    }

    private function _get_posted_expenditure_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'expenditure_head_id';
        $items[] = 'amount';
        $items[] = 'expenditure_via';
        $items[] = 'reference';
        $items[] = 'note';
        
        $data = elements($items, $_POST);  
        
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['expenditure_type'] = 'general';
            
            $school = $this->expenditure->get_school_by_id($data['school_id']);
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('accounting/expenditure/index');
            }
            
            $data['academic_year_id'] = $school->academic_year_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();                       
        }

        return $data;
    }

    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/expenditure/index');
        }
        
         $expenditure = $this->expenditure->get_single('expenditures', array('id' => $id));
        
        if ($this->expenditure->delete('expenditures', array('id' => $id))) {  
            
            create_log('Has been deleted expenditure : '.$expenditure->amount);             
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('accounting/expenditure/index/'.$expenditure->school_id);
        
    } 

    
    public function get_expenditure_head_by_school() {
        
        $school_id  = $this->input->post('school_id');
        $expenditure_head_id  = $this->input->post('expenditure_head_id');
         
        $expenditure_heads = $this->expenditure->get_list('expenditure_heads', array('school_id'=>$school_id));  
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($expenditure_heads)) {
            foreach ($expenditure_heads as $obj) {   
                
                $selected = $expenditure_head_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
                
            }
        }

        echo $str;
    }
    
     public function get_payment_method_by_school(){
        
        $school_id  = $this->input->post('school_id');
        $expenditure_via  = $this->input->post('expenditure_via');
        $payments = get_payment_methods($school_id);
        
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        
        if (!empty($payments)) {
            foreach ($payments as $key=>$value) {
                $selected = $key == $expenditure_via ? $select : '';
                $str .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
            }
        }
        echo $str;
        
    }
    
   
}
