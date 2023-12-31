<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Category_Model', 'category', true);
        
         // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_asset_management')){                        
              redirect('dashboard/index');
            }
        }
    }

    public function index($school_id = null) {
        
        check_permission(VIEW);        
        $this->data['categories'] = $this->category->get_category_list($school_id); 
        
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_category').' | ' . SMS);
        $this->layout->view('category/index', $this->data);  
    }

    public function add() {

        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_category_data();

                $insert_id = $this->category->insert('asset_categories', $data);
                if ($insert_id) {
                    
                    success($this->lang->line('insert_success'));
                    redirect('asset/category/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('asset/category/add');
                }
            } else {
                success($this->lang->line('insert_success'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['categories'] = $this->category->get_category_list(); 
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }


    public function edit($id = null) {       

        check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_category_data();
                $updated = $this->category->update('asset_categories', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    success($this->lang->line('update_success'));
                    redirect('asset/category/index/'.$data['school_id']); 
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('asset/category/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['category'] = $this->category->get_single('asset_categories', array('id' => $this->input->post('id')));
            }
        } 
        
        if ($id) {
            $this->data['category'] = $this->category->get_single('asset_categories', array('id' => $id));

            if (!$this->data['category']) {
                 redirect('asset/category/index');
            }
        }
       

        $this->data['categories'] = $this->category->get_category_list($this->data['category']->school_id); 
        $this->data['school_id'] = $this->data['category']->school_id;
        $this->data['filter_school_id'] = $this->data['category']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }

    private function _prepare_category_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_name');
        $this->form_validation->set_rules('note', $this->lang->line('note'));
    }

    public function name() {
        if ($this->input->post('id') == '') {
            $category = $this->category->duplicate_check($this->input->post('school_id'), $this->input->post('name'));
            if ($category) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $category = $this->category->duplicate_check($this->input->post('school_id'), $this->input->post('name'), $this->input->post('id'));
            if ($category) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    private function _get_posted_category_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'name';
        $items[] = 'note';
        $data = elements($items, $_POST); 
        
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
        
        if ($this->input->post('id')) {
            $data['status'] = $this->input->post('status');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
        }
        return $data;
    }    

    public function delete($id = null) {
        
        check_permission(DELETE);
         
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('asset/category/index');        
        }
        
        $category = $this->category->get_single('asset_categories', array('id' => $id));        
        if ($this->category->delete('asset_categories', array('id' => $id))) { 
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('asset/category/index/'.$category->school_id);
    }
}
