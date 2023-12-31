<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Issue extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Issue_Model', 'issue', true);  
        $this->data['roles'] = $this->issue->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_inventory')){                        
              redirect('dashboard/index');
            }
        }
    }

   
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Issue List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = NULL) {

        check_permission(VIEW);
                         
        $this->data['issues'] = $this->issue->get_issue_list($school_id);               
        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
           $condition['school_id'] = $this->session->userdata('school_id'); 
           $this->data['classes'] = $this->issue->get_list('classes', $condition, '','', '', 'id', 'ASC');
           $this->data['categories'] = $this->issue->get_list('item_categories', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $school_id;        
        $this->data['filter_school_id'] = $school_id;        
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_issue'). ' | ' . SMS);
        $this->layout->view('issue/index', $this->data);
        
    }   

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Issue" user interface                 
    *                    and process to store "Issue" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_issue_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_issue_data();

                $insert_id = $this->issue->insert('item_issues', $data);
                if ($insert_id) {
                    
                    $this->__update_stock();
                    
                    create_log('Has been added issue');                     
                    success($this->lang->line('insert_success'));
                    redirect('inventory/issue/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('inventory/issue/add');
                }
            } else {
                $this->data['post'] = $_POST;                
            }
        }
        
        $this->data['issues'] = $this->issue->get_issue_list();               
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
           $condition['school_id'] = $this->session->userdata('school_id'); 
           $this->data['classes'] = $this->issue->get_list('classes', $condition, '','', '', 'id', 'ASC');
           $this->data['categories'] = $this->issue->get_list('item_categories', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;        
        $this->layout->title($this->lang->line('add') .' | '. SMS);
        $this->layout->view('issue/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Issue" user interface                 
    *                    with populated "Issue" value 
    *                    and process to update "Issue" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('inventory/issue/index');
        }
       
        if ($_POST) {
            $this->_prepare_issue_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_issue_data();
                $updated = $this->issue->update('item_issues', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $this->__update_stock();
                    create_log('Has been updated issue');                    
                    success($this->lang->line('update_success'));
                    redirect('inventory/issue/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('inventory/issue/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['issue'] = $this->issue->get_single_issue($this->input->post('id'));
            }
        }

        
        if ($id) {
            
            $this->data['issue'] = $this->issue->get_single_issue($id);
            if (!$this->data['issue']) {
                redirect('inventory/issue/index');
            }
        }

        $this->data['issues'] = $this->issue->get_issue_list($this->data['issue']->school_id);               
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
           $condition['school_id'] = $this->session->userdata('school_id'); 
           $this->data['classes'] = $this->issue->get_list('classes', $condition, '','', '', 'id', 'ASC');
           $this->data['categories'] = $this->issue->get_list('item_categories', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['issue']->school_id;
        $this->data['filter_school_id'] = $this->data['issue']->school_id; 
        $this->data['schools'] = $this->schools; 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('inventory/issue/index', $this->data);
    }

       
           
     /*****************Function get_single_issue**********************************
     * @type            : Function
     * @function name   : get_single_issue
     * @description     : "Load single issue information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_issue(){
        
       $issue_id = $this->input->post('issue_id');   
       $this->data['issue'] = $this->issue->get_single_issue($issue_id);
       echo $this->load->view('issue/get-single-issue', $this->data);
    }

    
    /*****************Function _prepare_issue_validation**********************************
    * @type            : Function
    * @function name   : _prepare_issue_validation
    * @description     : Process "issue" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_issue_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('role_id', $this->lang->line('user_type'), 'trim|required');
        
        if($this->input->post('role_id') == STUDENT){
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        }
        
        $this->form_validation->set_rules('user_id', $this->lang->line('issue_to'), 'trim|required');
        $this->form_validation->set_rules('category_id', $this->lang->line('catagory'), 'trim|required');
        $this->form_validation->set_rules('product_id', $this->lang->line('product'), 'trim|required');
        $this->form_validation->set_rules('qty', $this->lang->line('quantity'),'trim|required|callback_qty');
        $this->form_validation->set_rules('issue_date', $this->lang->line('issue_date'), 'trim|required');
        $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }
    
    
    /*****************Function qty**********************************
    * @type            : Function
    * @function name   : qty
    * @description     : Unique check for "qty" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function qty() {
        
        $qty = $this->input->post('qty');
        $product_id = $this->input->post('product_id');
        $stock = $this->issue->get_single('item_stocks', array('product_id' => $product_id));
        
        if ($this->input->post('id') == '') {
                        
            if (!empty($stock) && $qty > $stock->total_qty) {
                $this->form_validation->set_message('qty', $this->lang->line('insufficient_quantity'));
                return FALSE;
            }else if (empty($stock)) {
                $this->form_validation->set_message('qty', $this->lang->line('insufficient_quantity'));
                return FALSE;
            } else {
                return TRUE;
            }
            
        } else if ($this->input->post('id') != '') {
            
            $old_qty = $this->input->post('old_qty');            
            $old_product_id = $this->input->post('old_product_id');
             
             if ($product_id == $old_product_id) {
                
                $total = $stock->total_qty + $old_qty;
                
                if ($qty > $total) {
                    $this->form_validation->set_message('qty', $this->lang->line('insufficient_quantity'));
                    return FALSE;
                } else {
                    return TRUE;
                }
                
             }else if ($product_id != $old_product_id) {
                               
                if ($qty > $stock->total_qty) {
                    $this->form_validation->set_message('qty', $this->lang->line('insufficient_quantity'));
                    return FALSE;
                } else {
                    return TRUE;
                }               
             }            
        }
    }

    
                        
   
    
    
    /*****************Function _get_posted_issue_data**********************************
    * @type            : Function
    * @function name   : _get_posted_issue_data
    * @description     : Prepare "Issue" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_issue_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'role_id';
        $items[] = 'user_id';
        $items[] = 'class_id';
        $items[] = 'category_id';
        $items[] = 'product_id';
        $items[] = 'qty';
        $items[] = 'note';

        $data = elements($items, $_POST);
        
        $data['issue_date'] = date('Y-m-d', strtotime($this->input->post('issue_date')));
        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date')));
        $data['return_date'] = NULL;
        
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
        
        if($this->input->post('id')) {
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();            
        }

        return $data;
    }

    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Issue" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('inventory/issue/index');
        }
        
        $issue = $this->issue->get_single('item_issues', array('id' => $id));
        
        if ($this->issue->delete('item_issues', array('id' => $id))) {
            
             // return item qty to main qty
            if(!$issue->return_date){
                $sql = "UPDATE item_stocks SET total_qty = total_qty+$issue->qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $issue->product_id";
                $this->db->query($sql);
            }
            
            success($this->lang->line('delete_success'));
            redirect('inventory/issue/index/'.$issue->school_id);
            
        } else {
            error($this->lang->line('delete_failed'));
        }   
        
        redirect('inventory/issue/index');
    }
    
    
        
    private function __update_stock(){
                
        $data = array();
        $school_id = $this->input->post('school_id');
        $product_id = $this->input->post('product_id');
        $old_product_id = $this->input->post('old_product_id');               
        $qty = $this->input->post('qty');
        
        if ($this->input->post('id') && $product_id == $old_product_id) {
            
            $old_qty = $this->input->post('old_qty');
            
            $sql = "UPDATE item_stocks SET total_qty = total_qty+$old_qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $product_id AND school_id = $school_id";
            $this->db->query($sql);
            
            $sql = "UPDATE item_stocks SET total_qty = total_qty-$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $product_id AND school_id = $school_id";
            $this->db->query($sql);
            
        }else if ($this->input->post('id') && $product_id != $old_product_id) {
            
            $old_qty = $this->input->post('old_qty');
            
            // for old product
            $sql = "UPDATE item_stocks SET total_qty = total_qty+$old_qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $old_product_id AND school_id = $school_id";
            $this->db->query($sql);
            
            // for new item 
            $sql = "UPDATE item_stocks SET total_qty = total_qty-$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $product_id AND school_id = $school_id";
            $this->db->query($sql);  
                        
        }else{
            
            $sql = "UPDATE item_stocks SET total_qty = total_qty-$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $product_id AND school_id = $school_id";
            $this->db->query($sql);
        }        
    }
    
        
    /*****************Function issue_check_out**********************************
    * @type            : Function
    * @function name   : issue_check_out
    * @description     : Process to ceckout for a asset                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function issue_check_out() {
        
        $issue_id = $this->input->post('issue_id');

        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
        $data['return_date'] = date('Y-m-d H:i:s');

        $issue = $this->issue->get_single('item_issues', array('id' => $issue_id));
        
        if($this->issue->update('item_issues', $data, array('id' => $issue_id))){
            
             // return item qty to main qty
            $sql = "UPDATE item_stocks SET total_qty = total_qty+$issue->qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE product_id = $issue->product_id";
            $this->db->query($sql);
            
            echo TRUE;
        }else{            
            echo FALSE;  
        }
    }
    
    /*****************Function get_category_by_school**********************************
     * @type            : Function
     * @function name   : get_category_by_school
     * @description     : Load "Category Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_category_by_school() {
        
        $school_id  = $this->input->post('school_id');
        $category_id  = $this->input->post('category_id');
        
        $categories = $this->issue->get_list('item_categories', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($categories)) {
            foreach ($categories as $obj) {   
                
                $selected = $category_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
    
   /*** *************Function get_product_by_category**********************************
     * @type            : Function
     * @function name   : get_product_by_category
     * @description     : this function used to populate Product list by class 
      for user interface
     * @param           : null 
     * @return          : $str string  value with subject list
     * ********************************************************** */
    
    public function get_product_by_category(){
        
       $school_id  = $this->input->post('school_id'); 
       $category_id = $this->input->post('category_id');
       $product_id = $this->input->post('product_id');  
       
       $products = $this->issue->get_list('item_products', array('status' => 1, 'category_id' => $category_id, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
       
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        $select = 'selected="selected"';
        
        if (!empty($products)) {
            foreach ($products as $obj) {
                $selected = $product_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' </option>';
            }
        }

        echo $str;
    
    }     
}