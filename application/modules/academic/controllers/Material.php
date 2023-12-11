<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Material extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        $this->load->model('Material_Model', 'material', true);  
    }

    public function index($class_id = null) {
        
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
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
        
        $this->data['materials'] = $this->material->get_material_list($class_id, $school_id);            
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_study_material') . ' | ' . SMS);
        $this->layout->view('material/index', $this->data);            
       
    }

    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_material_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_material_data();

                $insert_id = $this->material->insert('study_materials', $data);
                if ($insert_id) {
                    
                       
                    $class = $this->material->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been added material : '. $data['title'].' for class : '. $class->name);
                    
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/material/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/material/add/'.$data['class_id']);
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
        $this->data['materials'] = $this->material->get_material_list($class_id);            

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
    }

    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_material_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_material_data();
                $updated = $this->material->update('study_materials', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->material->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated material : '. $data['title'].' for class : '. $class->name);                    
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/material/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/material/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['material'] = $this->material->get_single('study_materials', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['material'] = $this->material->get_single('study_materials', array('id' => $id));

            if (!$this->data['material']) {
               redirect('academic/material/index');
            }
        }
        
        $class_id = $this->data['material']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['materials'] = $this->material->get_material_list($class_id, $this->data['material']->school_id);            
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['material']->school_id;
        $this->data['filter_school_id'] = $this->data['material']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
        
    }

    public function view( $material_id = null ){
        check_permission(VIEW);
        
        if(!is_numeric($material_id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
        }
        
        $this->data['material'] = $this->material->get_single_material( $material_id);
        $class_id = $this->data['material']->class_id;
        
        $this->data['materials'] = $this->material->get_material_list($class_id);    

         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }
        $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('material'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
    }

    public function get_single_material(){
        
       $material_id = $this->input->post('material_id');
       
       $this->data['material'] = $this->material->get_single_material( $material_id);
       echo $this->load->view('material/get-single-material', $this->data);
    }

    private function _prepare_material_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');   
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required');   
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim');   
        $this->form_validation->set_rules('material', $this->lang->line('material'), 'trim|callback_material');   
    }

   public function material()
   {  
        if($_FILES['material']['name'])
        { 
           $name = $_FILES['material']['name'];           
           $ext = pathinfo($name, PATHINFO_EXTENSION);
           if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'txt'|| $ext == 'xls' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
               return TRUE;
           } else {
               $this->form_validation->set_message('material', $this->lang->line('select_valid_file_format').' Ex: jpg, jpeg, png, gif, doc, docx, pdf, ppt, pptx, xls, txt');         
               return FALSE; 
           }         
        }
       
   }

    private function _get_posted_material_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'subject_id';
        $items[] = 'title';
        $items[] = 'description';
        
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
        }
        
        
        if (isset($_FILES['material']['name'])) {
           $data['material'] = $this->_upload_material();
        }
        
        return $data;
    }

    private function _upload_material(){
           
        $prev_material     = $this->input->post('prev_material');
        $material          = $_FILES['material']['name'];
        $material_type     = $_FILES['material']['type'];
        $return_material   = '';

        if ($material != "") {

                $destination = 'assets/uploads/material/';               

                $file_type  = explode(".", $material);
                $extension  = strtolower($file_type[count($file_type) - 1]);
                $material_path = 'material-'.time() . '-gsms.' . $extension;

                move_uploaded_file($_FILES['material']['tmp_name'], $destination . $material_path);

                // need to unlink previous material
                if ($prev_material != "") {
                    if (file_exists($destination . $prev_material)) {
                        @unlink($destination . $prev_material);
                    }
                }

                $return_material = $material_path;
            
        } else {
            $return_material = $prev_material;
        }

        return $return_material;
    }

    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
        }
        
        $material = $this->material->get_single('study_materials', array('id' => $id));
        if ($this->material->delete('study_materials', array('id' => $id))) {   
            
               
            $class = $this->material->get_single('classes', array('id' => $material->class_id, 'school_id'=>$material->school_id));
            create_log('Has been deleted a material : '. $material->title.' for class:'. $class->name);
            
            
            // delete material file
            $destination = 'assets/uploads/';
            if (file_exists( $destination.'/material/'.$material->material)) {
                @unlink($destination.'/material/'.$material->material);
            }
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/material/index/'.$material->class_id);
    }
    
    
    

}
