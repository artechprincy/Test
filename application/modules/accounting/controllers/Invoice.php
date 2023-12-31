<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice extends MY_Controller {

    public $data = array();    
    
    function __construct() {
        
        parent::__construct();
         $this->load->model('Invoice_Model', 'invoice', true);
         $this->load->model('Payment_Model', 'payment', true);
         
          // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_accounting')){                        
              redirect('dashboard/index');
            }
        }
    }

    public function index($invoice_id = null) {
        
        check_permission(VIEW);       
        //for super admin        
        $school_id = '';
        if($_POST){   
            $school_id = $this->input->post('school_id');                   
        }
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id']);
        }
        
         // default global income head       
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id); 
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        $this->data['invoice_id'] = $invoice_id;
         
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);            
       
    }
    

    public function view($id = null) {
        
        check_permission(VIEW);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }
     
        $txn_amount                = $this->payment->get_invoice_amount($id);        
        $this->data['paid_amount'] = $txn_amount->paid_amount;
        $this->data['invoice'] = $this->invoice->get_single_invoice($id);
        $this->data['invoice_items'] = $this->invoice->get_invoice_item($id);
        
              
        $school_id = $this->data['invoice']->school_id;
        $this->data['school']   = $this->invoice->get_school_by_id($school_id);
      
        $this->layout->title($this->lang->line('view') . ' | ' . SMS);
        $this->layout->view('invoice/view', $this->data);            
       
    }

    public function get_single_invoice() {    
        
        $id = $this->input->post('invoice_id'); 
        
        $txn_amount                = $this->payment->get_invoice_amount($id);        
        $this->data['paid_amount'] = $txn_amount->paid_amount;
        $this->data['invoice'] = $this->invoice->get_single_invoice($id);
        $this->data['invoice_items'] = $this->invoice->get_invoice_item($id, $this->data['invoice']->invoice_type);
        $school_id = $this->data['invoice']->school_id;
        $this->data['settings']   = $this->invoice->get_school_by_id($school_id);
        
        if($this->data['invoice']->invoice_type == 'sale'){
            
            $this->data['sale'] = $this->data['invoice'];
            $this->data['sale_items'] = $this->data['invoice_items'];
            echo $this->load->view('inventory/sale/get-single-sale', $this->data); 
            
        }else{
            echo $this->load->view('invoice/get-single-invoice', $this->data);            
        }
    }

    public function due($school_id = null) {    
        
        check_permission(VIEW);
              
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id, 'due');  
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('due_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/due', $this->data);            
       
    }

    public function add($school_id = null) {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $invoice_id = $this->_get_posted_invoice_data();
               
                if ($invoice_id) {                  
                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/index/'.$invoice_id);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
            
            $school_id = $this->input->post('school_id');
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id']);
        }
        
        // default global income head
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id); 
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;  
        
        $this->data['single'] = TRUE;
        $this->layout->title($this->lang->line('create_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }


    public function bulk($school_id = null) {

        check_permission(ADD);
        
        if ($_POST) {
           
            $this->_prepare_invoice_validation();           
            if ($this->form_validation->run() === TRUE) {
               
                $status = $this->_get_create_bulk_invoice();
                if ($status) {
                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/index');
                    
                } else {                  
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/bulk');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
            
            $school_id = $this->input->post('school_id');
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id']);
        }
        
        // default global income head
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id); 
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;    
        
        $this->data['bulk'] = TRUE;
        $this->layout->title($this->lang->line('create_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
             redirect('accounting/invoice/index');
        }
        
        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_invoice_data();
                $updated = $this->invoice->update('invoices', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a invoice : '. $data['net_amount']);
                    
                    success($this->lang->line('update_success'));
                    redirect('accounting/invoice/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/invoice/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $id));

            if (!$this->data['invoice']) {
                 redirect('accounting/invoice/index');
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        // default global income head
        $this->data['income_heads'] = $this->invoice->get_list('income_heads', array('status'=> 1), '','', '', 'id', 'ASC');        
        $this->data['invoices'] = $this->invoice->get_invoice_list(); 
        
        $this->data['school_id'] = $this->data['invoice']->school_id;

        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    private function _prepare_invoice_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');               
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('paid_status', $this->lang->line('paid_status'), 'trim|required'); 
        
        if($this->input->post('type')== 'single'){
            $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required'); 
            $this->form_validation->set_rules('amount', $this->lang->line('fee_amount'), 'trim|required'); 
                       
        }
        
        $this->form_validation->set_rules('is_applicable_discount', $this->lang->line('is_applicable_discount'), 'trim|required');   
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required');   
        $this->form_validation->set_rules('paid_status', $this->lang->line('paid_status'), 'trim|required');   
        
        if($this->input->post('paid_status')== 'paid'){
           $this->form_validation->set_rules('payment_method', $this->lang->line('payment_method'), 'trim|required');   
        }
        
    }

    private function _get_posted_invoice_data() {

        $discount = array();
        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'student_id';
        $items[] = 'is_applicable_discount';  
        $items[] = 'month';        
        $items[] = 'paid_status';        
        $items[] = 'note';
        
        $data = elements($items, $_POST);          
                            
        $data['discount'] = 0.00;
        $data['gross_amount'] = $this->input->post('amount');
        $data['net_amount']   = $this->input->post('amount');
        $data['invoice_type'] = 'invoice';
        
        if(!isset($_POST['income_head_id'])){
            error($this->lang->line('select').' '.$this->lang->line('income_head'));
            redirect('accounting/invoice/add');
        }
        
        if($data['is_applicable_discount']){
            
            $discount = $this->invoice->get_student_discount($data['student_id']);
            if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                $data['discount']   = ($discount->amount/100)*$data['gross_amount'];
                $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                
            }elseif(!empty($discount) && $discount->discount_type == 'flat'){
                
                $data['discount']   = $discount->amount;
                $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                
            }
        }
        
        $data['date'] = date('Y-m-d');  
            
        $data['custom_invoice_id'] = $this->invoice->get_custom_id('invoices', 'INV');
        $data['status'] = 1;

        $school = $this->invoice->get_school_by_id($data['school_id']);

        if(!$school->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('accounting/invoice/index');
        }             

        $data['academic_year_id'] = $school->academic_year_id;

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();   
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
                
         
        // save invoice data
         $student = $this->invoice->get_single('students', array('id' => $data['student_id']));
         $inv_data = $data;
         $inv_data['user_id'] =  $student->user_id;
         $inv_data['role_id'] =  STUDENT;
         unset($inv_data['student_id']);
         $invoice_id = $this->invoice->insert('invoices', $inv_data);
         
        // save invoice detail data
        foreach ($this->input->post('income_head_id') as $key=>$value){
            
            $inv_detail = array();
            $inv_detail['school_id'] = $data['school_id'];
            $inv_detail['invoice_id'] = $invoice_id;
            $inv_detail['income_head_id'] = $key;
            $inv_detail['invoice_type'] = $value;
            
            $income_head = $this->invoice->get_single('income_heads', array('id' => $key, 'school_id'=>$data['school_id']));
                
            if($income_head->head_type == 'hostel' && $student->is_hostel_member == 0){
                continue;
            }elseif($income_head->head_type == 'transport' && $student->is_transport_member == 0){
                continue;
            }
            
                        
            $amt = $this->__get_fee_amount($data['school_id'], $key, $data['student_id'], $data['class_id'], $income_head);
            $inv_detail['gross_amount'] = $amt;
            $inv_detail['discount'] = 0.00;
            $inv_detail['net_amount'] = $amt;
                
            if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                $inv_detail['discount']   = ($discount->amount/100)*$amt;
                $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
                               
            }elseif(!empty($discount) && $discount->discount_type == 'flat'){
                
                $inv_detail['discount']   = ($discount->amount/$data['gross_amount'])*$amt;
                $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
            }
                        
            $inv_detail['status'] = 1;
            $inv_detail['created_at'] = date('Y-m-d H:i:s');
            $inv_detail['created_by'] = logged_in_user_id();   
            $inv_detail['modified_at'] = date('Y-m-d H:i:s');
            $inv_detail['modified_by'] = logged_in_user_id();
            $this->invoice->insert('invoice_detail', $inv_detail);
            
        }
        
         // save transction table data
        $data['invoice_id'] = $invoice_id;
        $this->_save_transaction($data);
        
        create_log('Has been created a invoice : '. $data['net_amount']);
        return $invoice_id;
    }

    private function _get_create_bulk_invoice() {
        
        $discount = array();
        $data = array();
       
        $items[] = 'school_id';
        $items[] = 'class_id';       
        $items[] = 'is_applicable_discount';  
        $items[] = 'month'; 
        $items[] = 'paid_status';
        $items[] = 'note';
        
        $data = elements($items, $_POST);         
                
        $data['date'] = date('Y-m-d');            
        $data['discount'] = 0.00;
        $data['status'] = 1;
        $data['invoice_type'] = 'invoice';
        
        $school = $this->invoice->get_school_by_id($data['school_id']);
        
        if(!$school->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('accounting/invoice/bulk');
        } 
        
        $data['academic_year_id'] = $school->academic_year_id;
        
        if(!isset($_POST['income_head_id'])){
            error($this->lang->line('select').' '.$this->lang->line('income_head'));
            redirect('accounting/invoice/bulk');
        }
        
        if(!isset($_POST['students'])){
            error($this->lang->line('select_student'));
            redirect('accounting/invoice/bulk');
        }
      
        if(count($this->input->post('students')) > 0){
            foreach ($this->input->post('students') as $student_id=>$value){

                $data['student_id'] = $student_id;            
                $data['gross_amount'] = $value;
                $data['net_amount'] = $value;

                if($data['is_applicable_discount']){

                    $discount = $this->invoice->get_student_discount($data['student_id']);
                    if(!empty($discount) && $discount->discount_type == 'percentage'){

                        $data['discount']   = ($discount->amount/100)*$data['gross_amount'];
                        $data['net_amount'] = $data['gross_amount'] - $data['discount'];

                    }elseif(!empty($discount) && $discount->discount_type == 'flat'){

                        $data['discount']   = $discount->amount;
                        $data['net_amount'] = $data['gross_amount'] - $data['discount'];

                    }
                }

                $data['custom_invoice_id'] = $this->invoice->get_custom_id('invoices', 'INV');

                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = logged_in_user_id();
                $data['modified_at'] = date('Y-m-d H:i:s');
                $data['modified_by'] = logged_in_user_id();



                $student = $this->invoice->get_single('students', array('id' => $student_id));            
                $inv_data = $data;
                $inv_data['user_id'] =  $student->user_id;
                $inv_data['role_id'] =  STUDENT;
                unset($inv_data['student_id']);
                $invoice_id = $this->invoice->insert('invoices', $inv_data);

                 // save invoice detail data
                foreach ($this->input->post('income_head_id') as $key=>$value){

                    $inv_detail = array();
                    $inv_detail['school_id'] = $data['school_id'];
                    $inv_detail['invoice_id'] = $invoice_id;
                    $inv_detail['income_head_id'] = $key;
                    $inv_detail['invoice_type'] = $value;

                    $income_head = $this->invoice->get_single('income_heads', array('id' => $key, 'school_id'=>$data['school_id']));

                    if($income_head->head_type == 'hostel' && $student->is_hostel_member == 0){
                        continue;
                    }elseif($income_head->head_type == 'transport' && $student->is_transport_member == 0){
                        continue;
                    } 

                    $amt = $this->__get_fee_amount($data['school_id'], $key, $data['student_id'], $data['class_id'], $income_head);
                    $inv_detail['gross_amount'] = $amt;
                    $inv_detail['discount'] = 0.00;
                    $inv_detail['net_amount'] = $amt;

                    if(!empty($discount) && $discount->discount_type == 'percentage'){

                        $inv_detail['discount']   = ($discount->amount/100)*$amt;
                        $inv_detail['net_amount'] = $amt - $inv_detail['discount'];

                    }elseif(!empty($discount) && $discount->discount_type == 'flat'){

                        $inv_detail['discount']   = ($discount->amount/$data['gross_amount'])*$amt;
                        $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
                    }

                    $inv_detail['status'] = 1;
                    $inv_detail['created_at'] = date('Y-m-d H:i:s');
                    $inv_detail['created_by'] = logged_in_user_id();   
                    $inv_detail['modified_at'] = date('Y-m-d H:i:s');
                    $inv_detail['modified_by'] = logged_in_user_id();
                    $this->invoice->insert('invoice_detail', $inv_detail);
                }


                // save transction table data
                $txn = array(); 
                $txn = $data;
                $txn['invoice_id'] = $invoice_id;
                $this->_save_transaction($txn);

                // reset some data
                $data['gross_amount'] = 0;
                $data['net_amount'] = 0;
                $data['discount'] = 0;
                $inv_detail['discount'] = 0;
            }

            $class = $this->invoice->get_single('classes', array('id' => $this->input->post('class_id')));
            create_log('Has been created for class '. $class->name);
        }
        return TRUE; 
    }

    public function delete($id = null) {
        
        check_permission(DELETE);
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
             redirect('accounting/invoice/index');
        } 
                
        $invoice = $this->invoice->get_single('invoices', array('id' => $id));
        
        if ($this->invoice->delete('invoices', array('id' => $id))) {  
            
            $this->invoice->delete('invoice_detail', array('invoice_id' => $id));
            $this->invoice->delete('transactions', array('invoice_id' => $id));
            
            create_log('Has been deleted a invoice : '. $invoice->net_amount);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('accounting/invoice/index/'.$invoice->school_id);
    }

    private function _save_transaction($data){
        
        if($data['paid_status'] == 'paid'){
        
            $txn = array();
            $txn['school_id'] = $data['school_id'];  
            $txn['amount'] = $data['net_amount'];  
            $txn['note'] = $data['note'];
            $txn['payment_date'] = $data['date'];
            $txn['payment_method'] = $this->input->post('payment_method');
            $txn['bank_name'] = $this->input->post('bank_name');
            $txn['cheque_no'] = $this->input->post('cheque_no');

            if ($this->input->post('id')) {

                $txn['modified_at'] = date('Y-m-d H:i:s');
                $txn['modified_by'] = logged_in_user_id();
                $this->invoice->update('transactions', $txn, array('invoice_id'=>$this->input->post('id')));

            } else {            

                $txn['invoice_id'] = $data['invoice_id'];
                $txn['status'] = 1;
                $txn['academic_year_id'] = $data['academic_year_id'];            
                $txn['created_at'] = $data['created_at'];
                $txn['created_by'] = $data['created_by'];
                $this->invoice->insert('transactions', $txn);
            }        
        }
    }
    
    
    
    /* Ajax */
    
    // single
    public function get_single_fee_type_by_school(){
        
        $school_id = $this->input->post('school_id');        
        $income_heads = $this->invoice->get_fee_type($school_id);
         
        $str = '';
        if (!empty($income_heads)) {
            foreach ($income_heads as $obj) { 
                
                $str .= '<input onclick="get_single_fee_amount('.$obj->id.')" type="checkbox" name="income_head_id['.$obj->id.']" id="income_head_id_'.$obj->id.'" class="fn_income_head_id" value="'.$obj->head_type.'" > '.$obj->title.'<br/>';
            }
        }

        echo $str;
    }
    
    // single
    public function get_single_fee_amount(){
        
        $school_id = $this->input->post('school_id'); 
        $class_id       = $this->input->post('class_id');       
        $income_head_id = $this->input->post('income_head_id');
        $student_id = $this->input->post('student_id');
        $amount = $this->input->post('amount');
        $check_status = $this->input->post('check_status');
        
        $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id, 'school_id'=>$school_id));
        
        $amt = $this->__get_fee_amount($school_id, $income_head_id, $student_id, $class_id, $income_head);
        
        if($check_status == 'true'){
           echo $amount+$amt;
        }else{
            echo $amount-$amt;
        } 
    }
    
    
    // bulk
    public function get_bulk_fee_type_by_school(){
        
        $school_id = $this->input->post('school_id');        
        $income_heads = $this->invoice->get_fee_type($school_id);
         
        $str = '';
        if (!empty($income_heads)) {
            foreach ($income_heads as $obj) { 
                
                $str .= '<input onclick="get_bulk_fee_amount('.$obj->id.')" type="checkbox" itemid="'.$obj->id.'" name="income_head_id['.$obj->id.']" id="income_head_id_'.$obj->id.'" class="fn_income_head_id" value="'.$obj->head_type.'">&nbsp;'.$obj->title.'<br/>';
            }
        }

        echo $str;
    } 
    
    // bulk
    public function get_bulk_fee_amount(){
                
        $school_id      = $this->input->post('school_id');
        $class_id       = $this->input->post('class_id');       
        $head_ids       = rtrim($this->input->post('head_ids'), ',');
        
       
        
        $school = $this->invoice->get_school_by_id($school_id);
        
        if(!$school->academic_year_id){  echo 'ay';   die();  } 
                    
        $students = $this->invoice->get_student_list($school_id, $school->academic_year_id, $class_id, '', 'regular'); 
       
        $student_str = $this->lang->line('no_data_found');
        
        if(!empty($students) && $head_ids != ''){            
            
            $student_str = '';
            $head_ids_arr = explode(',', $head_ids);
             
            foreach($students as $obj){                
               
                $amount = 0.00;
                foreach($head_ids_arr as $income_head_id){
                    
                    $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id, 'school_id'=>$school_id));
                    $amount += $this->__get_fee_amount($school_id, $income_head_id, $obj->id, $class_id, $income_head);                
                }                
                
                // making student string....
                $student_str .= '<div class="multi-check"><input type="checkbox" class="studentchk" name="students['.$obj->id.']" value="'.$amount.'" /> '.$obj->name.' ['.$school->currency_symbol.$amount.']</div>';
            }
        }
        
        echo $student_str;
    }

    
    // common
    private function __get_fee_amount($school_id, $income_head_id, $student_id, $class_id, $income_head){
        
        $amt = 0.00;
                
        if($income_head->head_type == 'hostel'){
            
            $fee = $this->invoice->get_hostel_fee($student_id);            
            if(!empty($fee)){
                $amt += $fee->cost;
            }            
            
        }elseif($income_head->head_type == 'transport'){
            
            $fee = $this->invoice->get_transport_fee($student_id);            
            if(!empty($fee)){
                $amt += $fee->stop_fare;
            }
            
        }else{
            
            $fee = $this->invoice->get_single('fees_amount', array('school_id'=>$school_id, 'class_id' => $class_id, 'income_head_id'=>$income_head_id));
            if(!empty($fee)){
                $amt += $fee->fee_amount;
            }
        }
        
        return $amt;
    }


    
    public function get_student_by_class() {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $student_id = $this->input->post('student_id');
        $is_bulk = $this->input->post('is_bulk');
         
        $school = $this->invoice->get_school_by_id($school_id);
        $students = $this->invoice->get_student_list($school_id, $school->academic_year_id, $class_id, $student_id, 'regular');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if($is_bulk){
             $str .= '<option value="all">' . $this->lang->line('all') . '</option>';
        }
        
        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }

    
    public function get_fee_type_by_school(){
        
        $school_id = $this->input->post('school_id');
        $fee_type_id = $this->input->post('fee_type_id');
        
        $income_heads = $this->invoice->get_fee_type($school_id);
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($income_heads)) {
            foreach ($income_heads as $obj) {   
                
                $selected = $fee_type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title .' </option>';
                
            }
        }

        echo $str;
    }

    public function receipt() {    
        
        check_permission(VIEW);
         
        $this->data['receipts'] = '';
        if ($_POST) {
             
            $school_id = $this->input->post('school_id'); 
            $class_id = $this->input->post('class_id'); 
            $section_id = $this->input->post('section_id'); 
            $student_id = $this->input->post('student_id');
            
            $this->data['school'] = $this->invoice->get_school_by_id($school_id);            
            $this->data['receipts'] = $this->invoice->get_receipt_list($school_id, $class_id, $section_id, $student_id, $this->data['school']->academic_year_id);
            
            $this->data['school_id'] = $school_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['student_id'] = $student_id;
            
         }
        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');             
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_receipt') . ' | ' . SMS);
        $this->layout->view('invoice/receipt', $this->data);            
       
    }

    public function get_single_receipt() {    
        
        check_permission(VIEW);
        
        $txn_id = $this->input->post('txn_id'); 
        $school_id = $this->input->post('school_id'); 
        $class_id = $this->input->post('class_id'); 
        $section_id = $this->input->post('section_id'); 
        $student_id = $this->input->post('student_id');

        $this->data['school'] = $this->invoice->get_school_by_id($school_id);            
        $this->data['receipt'] = $this->invoice->get_single_receipt($school_id, $class_id, $section_id, $student_id, $this->data['school']->academic_year_id, $txn_id);
        $this->data['invoice_items'] = $this->invoice->get_invoice_item($this->data['receipt']->inv_id);
    
        echo $this->load->view('invoice/get-single-receipt', $this->data);
    }    
   
}
