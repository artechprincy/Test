<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme extends My_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Theme_Model', 'theme', true);
        $this->data['themes'] = $this->theme->get_list('themes', array('status' => 1), '', '', '', 'id', 'ASC');
    }

    public function index() {

        check_permission(VIEW);

        $this->layout->title($this->lang->line('theme') . ' | ' . SMS);
        $this->layout->view('theme', $this->data);
    }

    public function activate($id = null) {

        check_permission(EDIT);

        // need to check school subscription status
        if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            if(!check_saas_status($this->session->userdata('school_id'), 'is_enable_theme')){                        
              redirect('dashboard/index');
            }
        }
            
        if ($id == '') {
            error($this->lang->line('update_failed'));
            redirect('theme/index');
        }

        $theme = $this->theme->get_single('themes', array('id' => $id));
        
        if($this->session->userdata('role_id') == SUPER_ADMIN){
            //$this->theme->update('system_admin', array('theme_name' => $theme->slug), array('id' => logged_in_user_id()));
            $this->theme->update('global_setting', array('theme_name' => $theme->slug), array());
        }else{
            $this->theme->update('schools', array('theme_name' => $theme->slug), array('id' => $this->session->userdata('school_id')));
        }
        
        $this->session->unset_userdata('theme');
        $this->session->set_userdata('theme', $theme->slug);
        success($this->lang->line('update_success'));
        
        create_log('Activate Theme '. $theme->slug);
        redirect('theme/index');
    }

}
