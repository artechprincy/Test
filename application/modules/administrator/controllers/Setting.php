<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Setting_Model', 'setting', true); 
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){ 
            error($this->lang->line('permission_denied'));
             redirect('dashboard/index');
        }
        
        $this->data['themes'] = $this->setting->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['setting'] = $this->setting->get_single('global_setting', array('status'=>1));
        $this->data['fields'] = $this->setting->get_table_fields('languages');
    }


    public function index() {

        check_permission(VIEW);
      
        
        $this->layout->title($this->lang->line('general_setting') . ' | ' . SMS);
        $this->layout->view('setting/index', $this->data);
    }

    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_setting_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_setting_data();

                $insert_id = $this->setting->insert('global_setting', $data);
                if ($insert_id) {
                    
                    $this->session->unset_userdata('theme');
                    $this->session->set_userdata('theme', $data['theme_name']);
                    
                    create_log('Has been created global setting');                     
                    success($this->lang->line('insert_success'));
                    redirect('administrator/setting/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/setting/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data = $_POST;
            }
        }
        
        $this->layout->title($this->lang->line('general_setting') . ' | ' . SMS);
        $this->layout->view('setting/index', $this->data);
    }

    public function edit($id = null) {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_setting_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_setting_data();
                $updated = $this->setting->update('global_setting', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $this->session->unset_userdata('theme');
                    $this->session->set_userdata('theme', $data['theme_name']);
                    
                    // update language file
                    $this->update_lang();                    
                    create_log('Has been updated global setting');                      
                    success($this->lang->line('update_success'));
                    redirect('administrator/setting/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('administrator/setting/edit/' . $this->input->post('id'));
                }
            }else{
                error($this->lang->line('update_failed'));
            }
        }
        
        $this->layout->title( $this->lang->line('general_setting') . ' | ' . SMS);
        $this->layout->view('setting/index', $this->data);
    }
    private function _prepare_setting_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('brand_title', $this->lang->line('brand_title'), 'trim|required');
        $this->form_validation->set_rules('brand_name', $this->lang->line('brand_name'), 'trim|required');
        $this->form_validation->set_rules('brand_footer', $this->lang->line('brand_footer'), 'trim');
        $this->form_validation->set_rules('language', $this->lang->line('language'), 'trim|required');
        $this->form_validation->set_rules('enable_rtl', $this->lang->line('enable_rtl'), 'trim|required');
        $this->form_validation->set_rules('enable_frontend', $this->lang->line('enable_frontend'), 'trim|required');
        $this->form_validation->set_rules('theme_name', $this->lang->line('theme'), 'trim|required');
        $this->form_validation->set_rules('date_format', $this->lang->line('date_format'), 'trim|required');
        $this->form_validation->set_rules('time_zone', $this->lang->line('default_time_zone'), 'trim|required');
        
        $this->form_validation->set_rules('logo', $this->lang->line('brand_logo'), 'trim|callback_logo'); 
        $this->form_validation->set_rules('favicon_icon', $this->lang->line('favicon_icon'), 'trim|callback_favicon_icon'); 
    }

    public function logo() {
        if ($_FILES['logo']['name']) {
                        
            list($width, $height) = getimagesize($_FILES['logo']['tmp_name']);
            if((!empty($width)) && $width > 100 || $height > 110){
                $this->form_validation->set_message('logo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
                       
            $name = $_FILES['logo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('logo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

    public function favicon_icon() {
        if ($_FILES['favicon_icon']['name']) {
            
            list($width, $height) = getimagesize($_FILES['favicon_icon']['tmp_name']);
            if((!empty($width)) && $width > 20 || $height > 20){
                $this->form_validation->set_message('favicon_icon', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
            
            $name = $_FILES['favicon_icon']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'ico') {
                return TRUE;
            } else {
                $this->form_validation->set_message('favicon_icon', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
        

    private function _get_posted_setting_data() {

        $items = array();
        $items[] = 'brand_title';
        $items[] = 'brand_name';
        $items[] = 'brand_footer';
        $items[] = 'language';
        $items[] = 'enable_rtl';
        $items[] = 'enable_frontend';
        $items[] = 'theme_name';
        $items[] = 'date_format';
        $items[] = 'time_zone';       
        $items[] = 'google_analytics';       
        $items[] = 'currency';       
        $items[] = 'currency_symbol';       
        
        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        if ($_FILES['logo']['name']) {
            $data['brand_logo'] = $this->_upload_brand_logo();
        }
   
        if ($_FILES['favicon_icon']['name']) {
            $data['favicon_icon'] = $this->_upload_favicon_icon();
        }
        
        return $data;
    }

    private function _upload_brand_logo() {

        $prevoius_logo = @$_POST['logo_prev'];
        $logo_name = $_FILES['logo']['name'];
        $logo_type = $_FILES['logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if ($logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                    $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                    $logo_type == 'image/x-png' || $logo_type == 'image/gif') {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time().'-brand-logo.' . $extension;

                copy($_FILES['logo']['tmp_name'], $destination . $logo_path);

                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }

    private function _upload_favicon_icon() {

        $prevoius_logo = @$_POST['favicon_icon_prev'];
        $logo_name = $_FILES['favicon_icon']['name'];
        $logo_type = $_FILES['favicon_icon']['type'];
        $logo = '';


        if ($logo_name != "") {
            if ($logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                    $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                    $logo_type == 'image/x-icon' || $logo_type == 'type="image/ico' ||
                    $logo_type == 'image/x-png' || $logo_type == 'image/gif') {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time().'-favicon-icon.' . $extension;

                copy($_FILES['favicon_icon']['tmp_name'], $destination . $logo_path);

                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }
}